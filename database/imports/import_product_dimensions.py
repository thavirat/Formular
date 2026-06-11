"""
One-off import: ProdWide/ProdLong/ProdHigh/ProdWeight -> tb_products + compute cube.
Source: Prod_Join_Cate_Join_Design_Join_Group V3.xlsx (sheet 'All in')
Match key: tb_products.code = ProdID
Mapping : ProdWide->width, ProdLong->length, ProdHigh->height, ProdWeight->weight
Cube    : (width*length*height)/1e9  (mm -> cubic metre, matches app convention)
Scope   : only rows whose W,L,H are all present and > 0
Safety  : writes a CSV backup of affected rows BEFORE updating; runs in one transaction.
"""
import os, csv, openpyxl, pymysql

XLSX = os.path.expandvars(r'%LOCALAPPDATA%\Temp\prod_v3.xlsx')
HERE = os.path.dirname(os.path.abspath(__file__))
BACKUP = os.path.join(HERE, 'backup_product_dimensions_before.csv')

def num(x):
    try:
        return float(x)
    except (TypeError, ValueError):
        return None

def load_env_pw():
    for line in open(os.path.join(HERE, '..', '..', '.env'), encoding='utf-8'):
        if line.startswith('DB_PASSWORD='):
            return line.strip().split('=', 1)[1]
    raise RuntimeError('DB_PASSWORD not found')

# 1) read excel -> rows with full dimensions
wb = openpyxl.load_workbook(XLSX, read_only=True, data_only=True)
ws = wb['All in']
updates = []  # (code, width, length, height, weight, cube)
for i, r in enumerate(ws.iter_rows(values_only=True)):
    if i == 0:
        continue
    pid = r[9]
    if pid in (None, '', 'NULL'):
        continue
    code = str(pid).strip()
    w, l, h, wt = num(r[14]), num(r[15]), num(r[16]), num(r[17])
    if None in (w, l, h) or w <= 0 or l <= 0 or h <= 0:
        continue
    cube = round(w * l * h / 1e9, 6)
    updates.append((code, w, l, h, (wt if wt is not None else 0), cube))
print(f'rows to update: {len(updates)}')

con = pymysql.connect(host='167.71.201.150', port=3306, user='fml_thavirat',
                      password=load_env_pw(), database='fml_demo', connect_timeout=15)
try:
    cur = con.cursor()

    # 2) backup current values of affected rows
    codes = [u[0] for u in updates]
    placeholders = ','.join(['%s'] * len(codes))
    cur.execute(f"SELECT code,width,length,height,weight,cube FROM tb_products WHERE code IN ({placeholders})", codes)
    rows = cur.fetchall()
    with open(BACKUP, 'w', newline='', encoding='utf-8') as f:
        wcsv = csv.writer(f)
        wcsv.writerow(['code', 'width', 'length', 'height', 'weight', 'cube'])
        wcsv.writerows(rows)
    print(f'backup written: {BACKUP} ({len(rows)} rows)')

    # 3) widen cube column to keep precision
    cur.execute("ALTER TABLE tb_products MODIFY cube decimal(12,6) NULL DEFAULT 0.000000")
    print('cube column -> decimal(12,6)')

    # 4) update in one transaction
    cur.executemany(
        "UPDATE tb_products SET width=%s, length=%s, height=%s, weight=%s, cube=%s WHERE code=%s",
        [(u[1], u[2], u[3], u[4], u[5], u[0]) for u in updates]
    )
    con.commit()
    print(f'updated rows (affected): {cur.rowcount}')

    # 5) quick verify
    cur.execute("SELECT code,width,length,height,weight,cube FROM tb_products WHERE code=%s", (updates[0][0],))
    print('sample after:', cur.fetchone())
finally:
    con.close()
