"""
One-off import: CONTENT (column T) from Packing List Master -> tb_products.content
Source : Packing List Master *.xls  (active sheet; row0=column numbers, row1=headers, data from row2)
Match  : tb_products.code = column A (PART-NO_FOEMULA)
Value  : column T (index 19, CONTENT_FORMULA = pieces per carton)
Dedup  : if a part no repeats, keep the largest usable T (>0)
Cube   : used at display time -> (width*length*height/1e9) * order_qty / content
Safety : writes a CSV backup of affected rows BEFORE updating; one transaction.

Usage  : python database/imports/import_product_content.py "<path-to.xls>"
"""
import os, sys, csv, xlrd, pymysql

HERE = os.path.dirname(os.path.abspath(__file__))
BACKUP = os.path.join(HERE, 'backup_product_content_before.csv')
XLS = sys.argv[1] if len(sys.argv) > 1 else os.path.expandvars(r'%LOCALAPPDATA%\Temp\packing_master.xls')

def load_env_pw():
    for line in open(os.path.join(HERE, '..', '..', '.env'), encoding='utf-8'):
        if line.startswith('DB_PASSWORD='):
            return line.strip().split('=', 1)[1]
    raise RuntimeError('DB_PASSWORD not found')

def parse_t(v):
    if isinstance(v, (int, float)) and v != '':
        return float(v)
    if isinstance(v, str) and v.strip() not in ('', '-'):
        try:
            return float(v.replace(',', ''))
        except ValueError:
            return None
    return None

# 1) read xls -> code => max usable T
wb = xlrd.open_workbook(XLS)
sh = wb.sheet_by_index(0)
by_code = {}
for r in range(2, sh.nrows):
    a = sh.cell_value(r, 0)
    if a in ('', None) or str(a).strip() == '':
        continue
    code = str(a).strip()
    t = parse_t(sh.cell_value(r, 19))
    if t is None or t <= 0:
        continue
    if code not in by_code or t > by_code[code]:
        by_code[code] = t
print(f'codes with usable T: {len(by_code)}')

con = pymysql.connect(host='167.71.201.150', port=3306, user='fml_thavirat',
                      password=load_env_pw(), database='fml_demo', connect_timeout=15)
try:
    cur = con.cursor()

    # add column if missing
    cur.execute(
        "SELECT 1 FROM information_schema.columns WHERE table_schema=%s AND table_name='tb_products' AND column_name='content' LIMIT 1",
        (con.db.decode() if isinstance(con.db, bytes) else 'fml_demo',))
    if not cur.fetchone():
        cur.execute("ALTER TABLE tb_products ADD COLUMN content decimal(10,2) NULL DEFAULT 0 AFTER cube")
        print('added column tb_products.content')

    # backup current content of matched rows
    codes = list(by_code.keys())
    ph = ','.join(['%s'] * len(codes))
    cur.execute(f"SELECT code, content FROM tb_products WHERE code IN ({ph})", codes)
    rows = cur.fetchall()
    with open(BACKUP, 'w', newline='', encoding='utf-8') as f:
        w = csv.writer(f); w.writerow(['code', 'content']); w.writerows(rows)
    print(f'backup written: {BACKUP} ({len(rows)} rows)')

    updated = not_found = 0
    for code, content in by_code.items():
        cur.execute("UPDATE tb_products SET content=%s WHERE code=%s", (content, code))
        if cur.rowcount > 0:
            updated += 1
        else:
            not_found += 1
    con.commit()
    print(f'updated: {updated} | not found (no matching code): {not_found}')
finally:
    con.close()
