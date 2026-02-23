<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Export Production #{{ $ProformaInvoice->doc_no }}</title>
    <style>
        @page {
            margin: 5mm 5mm 5mm 5mm; /* บน ขวา ล่าง ซ้าย (ปรับเลขให้น้อยลงเพื่อลดขอบ) */
        }

        body {
            font-family: 'garuda', sans-serif;
            font-size: 12px; /* ปรับขนาดให้พอดีหน้ากระดาษ */
            color: #333333;
            margin: 0;
            padding: 0;
        }

        /* --- Utility Classes --- */
        .text-center { text-align: center !important; }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .font-bold { font-weight: bold; }
        .mt-4 { margin-top: 25px; }

        /* --- Header Section --- */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-table td {
            border: none;
            padding: 2px;
            vertical-align: top;
        }
        .company-name {
            font-size: 24pt;
            font-weight: bold;
            color: #2c3e50;
            line-height: 1;
        }
        .document-type {
            font-size: 14pt;
            color: #7f8c8d;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        .info-label {
            display: inline-block;
            width: 100px;
            color: #2c3e50;
        }

        /* --- Content Table --- */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
        }
        .content-table th {
            background-color: #2c3e50; /* สีน้ำเงินเข้ม */
            color: #ffffff;
            padding: 10px 5px;
            border: 1px solid #2c3e50;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }
        .content-table td {
            border: 1px solid #bdc3c7; /* สีเทาอ่อน */
            padding: 7px 5px;
            vertical-align: top;
        }
        /* ทำสีสลับบรรทัดให้อ่านง่าย (Zebra stripes) */
        .content-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* --- Remark Box --- */
        .remark-box {
            background-color: #fdfbf7;
            border: 1px solid #e0e0e0;
            border-left: 5px solid #e67e22; /* แถบสีส้มเน้นข้อความ */
            padding: 15px;
            margin-top: 30px;
            border-radius: 4px;
        }
        .remark-title {
            font-weight: bold;
            color: #d35400;
            font-size: 12pt;
            margin-bottom: 5px;
        }
        .remark-list {
            margin: 0;
            padding-left: 20px;
            color: #444444;
        }
        .remark-list li {
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <table with="100%" class="header-table">
        <tr>
            <th width="80%" align="left" style="font-size: 150%;">
                FORMULA INTERTRADE CO.,LTD.
            </th>
            <td>
                <b>Tel</b> : 063-525-2242
            </td>
        </tr>
        <tr>
            <td>
                119 MOTORWAY ROAD THAP CHANG, SAPHAN SUNG, BANGKOK 10250, THAILAND
            </td>
            <td>
                <b>Fax</b> : -
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <th width="67%" style="font-size: 150%;" align="right">EXPORT ORDER FORM</th>
            <td align="right">
                PAGE 1 / 4
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <th width="50%" align="left">
                <b>DOCUMENT NO. :</b>
            </th>
            <td width="50%" align="left">
                <b>DATE :</b>
            </td>
        </tr>
        <tr>
            <th width="50%" align="left">
                <b>CUSTOMER :</b>
            </th>
            <td width="50%" align="left">
                <b>EXPECTED SHIPMENT DATE :</b>
            </td>
        </tr>
        <tr>
            <th width="50%" align="left">
                <b>COUNTRY :</b>
            </th>
            <td width="50%" align="left">
                <b>SHIPMENT BY :</b>
            </td>
        </tr>
        <tr>
            <th width="50%" align="left" valign="top" rowspan="5">
                <b>SHIPPING MARKS :</b>
            </th>
            <td width="50%" align="left">
                <b>PAYMENT BY :</b>
            </td>
        </tr>
        <tr>
            <td align="left">
                <b>LASTEST (LC) SHIPMENT :</b>
            </td>
        </tr>
        <tr>
            <td align="left">
                <b>PURCHASE ORDER NO. :</b>
            </td>
        </tr>
        <tr>
            <td align="left">
                <b>Priority. :</b>
            </td>
        </tr>
        <tr>
            <td align="left">
                <b>Remarks. :</b>
            </td>
        </tr>
    </table>


    <table class="content-table mt-4">
        <thead>
            <tr>
                <th width="5%">ITM</th>
                <th width="12%">Fac No.</th>
                <th width="10%">QUANTITY</th>
                <th width="17%">FORMULAR<br>PART NUMBER</th>
                <th width="11%">DRAWING</th>
                <th width="30%">DESCRIPTION</th>
                <th width="15%">CUSTOMER<br>P/NO.</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ProformaInvoice->products as $item)
            <tr>
                <td class="text-center" valign="middle">{{ $item->seq }}</td>
                <td class="text-center">{{ $item->fac_no ?? '-' }}</td>
                <td class="text-center font-bold">{{ number_format($item->qty) }}</td>
                <td class="text-center">{{ $item->part_no }}</td>
                <td class="text-center">{{ $item->drawing ?? '-' }}</td>
                <td class="text-left">{{ $item->detail_thai ?? $item->detail_eng }}</td>
                <td class="text-center">{{ $item->cus_code ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px;">ไม่มีรายการสินค้า</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="remark-box">
        <div class="remark-title">Remarks / เงื่อนไขการผลิต:</div>
        <ol class="remark-list">
            <li>CDS, PFC, CO, LM บรรจุกล่องแบบ "A" และไม่ต้องติดสติ๊กเกอร์ FORMULA ที่ตัวสินค้า</li>
            <li>CDS, PFC ทุกรายการ พิมพ์ JAY AIR ตามแบบที่วางไว้บนกล่อง</li>
            <li>ทุกรายการให้ติดสติ๊กเกอร์ JAY AIR พิมพ์ PART NO. และบาร์โค้ดลูกค้าตามที่วางไว้บนกล่อง</li>
            <li>PFC ทุกรุ่นที่เป็นสี BRONZE ไม่ต้องทำการพ่นสี</li>
            <li>สินค้าตู้แอร์ใช้กล่องแบบ "N"</li>
        </ol>
    </div>

</body>
</html>
