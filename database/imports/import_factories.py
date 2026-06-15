"""
One-off import: Factory (Fac No.) from Master files.
Source : database/imports/masters/Type_Master.xlsx , Prod_Master.xlsx
- Type_Master: GoodTypeID, GoodTypeCode(=Fac No.), GoodTypeName, GoodTypeNameEng
  -> upsert into tb_factories (code = GoodTypeCode)  [idempotent by code]
- Prod_Master: GoodCode(=tb_products.code), GoodTypeID(link)
  -> set tb_products.factory_id by matching code
Safety : backup affected products (code, factory_id) BEFORE update; one transaction.
"""
import os, csv, openpyxl, pymysql

HERE = os.path.dirname(os.path.abspath(__file__))
TYPE = os.path.join(HERE, 'masters', 'Type_Master.xlsx')
PROD = os.path.join(HERE, 'masters', 'Prod_Master.xlsx')
BACKUP = os.path.join(HERE, 'backup_factory_id_before.csv')

def pw():
    for line in open(os.path.join(HERE, '..', '..', '.env'), encoding='utf-8'):
        if line.startswith('DB_PASSWORD='):
            return line.strip().split('=', 1)[1]
    raise RuntimeError('no DB_PASSWORD')

def s(v):
    return '' if v is None else str(v).strip()

# 1) Type_Master -> rows
twb = openpyxl.load_workbook(TYPE, read_only=True, data_only=True).worksheets[0]
types = []  # (GoodTypeID, GoodTypeCode, name)
for i, r in enumerate(twb.iter_rows(values_only=True)):
    if i == 0:
        continue
    tid, code = r[0], s(r[1])
    if tid in (None, '') or code == '':
        continue
    name_en = s(r[3]); name_th = s(r[2])
    name = (name_en if name_en and name_en.upper() != 'NULL' else
            (name_th if name_th and name_th.upper() != 'NULL' else None))
    types.append((str(tid), code, name))
print('type rows:', len(types))

con = pymysql.connect(host='167.71.201.150', port=3306, user='fml_thavirat',
                      password=pw(), database='fml_demo', connect_timeout=15)
try:
    cur = con.cursor()

    # upsert factories by code -> map GoodTypeID => factory.id
    fac_by_type = {}
    for tid, code, name in types:
        cur.execute("SELECT id FROM tb_factories WHERE code=%s LIMIT 1", (code,))
        row = cur.fetchone()
        if row:
            cur.execute("UPDATE tb_factories SET name=%s, updated_at=NOW() WHERE id=%s", (name, row[0]))
            fid = row[0]
        else:
            cur.execute("INSERT INTO tb_factories (code, name, created_at, updated_at) VALUES (%s,%s,NOW(),NOW())", (code, name))
            fid = cur.lastrowid
        fac_by_type[tid] = fid
    print('factories upserted:', len(fac_by_type), '->', fac_by_type)

    # 2) Prod_Master -> code => factory_id
    pwb = openpyxl.load_workbook(PROD, read_only=True, data_only=True).worksheets[0]
    code_to_fac = {}
    no_type = 0
    for i, r in enumerate(pwb.iter_rows(values_only=True)):
        if i == 0:
            continue
        code = s(r[0]); tid = r[5]
        if code == '' or code.upper() == 'NULL':
            continue
        if tid in (None, '') or s(tid).upper() == 'NULL':
            no_type += 1
            continue
        fid = fac_by_type.get(str(tid))
        if fid is not None:
            code_to_fac[code] = fid
    print('products with mappable factory:', len(code_to_fac), '| no GoodTypeID:', no_type)

    # backup current factory_id of affected products
    codes = list(code_to_fac.keys())
    ph = ','.join(['%s'] * len(codes))
    cur.execute(f"SELECT code, factory_id FROM tb_products WHERE code IN ({ph})", codes)
    rows = cur.fetchall()
    with open(BACKUP, 'w', newline='', encoding='utf-8') as f:
        w = csv.writer(f); w.writerow(['code', 'factory_id']); w.writerows(rows)
    print('backup written:', BACKUP, f'({len(rows)} rows)')

    updated = not_found = 0
    for code, fid in code_to_fac.items():
        cur.execute("UPDATE tb_products SET factory_id=%s WHERE code=%s", (fid, code))
        if cur.rowcount > 0:
            updated += 1
        else:
            not_found += 1
    con.commit()
    print(f'updated factory_id: {updated} | not found: {not_found}')
finally:
    con.close()
