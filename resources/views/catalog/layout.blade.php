<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'FORMULA — แคตตาล็อกอะไหล่แอร์รถยนต์')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--ink:#182a3e;--muted:#6e7e8d;--line:#dde5eb;--blue:#005baa;--navy:#102d49;--bg:#f3f6f8}
*{box-sizing:border-box}body{margin:0;background:#fff;color:var(--ink);font:15px 'Kanit',Tahoma,sans-serif;line-height:1.65}
button,input,select{font:inherit}button,a,select{cursor:pointer}a{color:inherit;text-decoration:none}button{border:0}
.wrap{max-width:1240px;margin:auto;padding:0 32px}
.utility{background:var(--navy);color:#c2d1df;font-size:11px;letter-spacing:1.2px;padding:9px 0}
.utility .wrap,header .wrap,.sectionhead,.row{display:flex;align-items:center;justify-content:space-between;gap:20px}
header{border-bottom:1px solid var(--line);background:white}header .wrap{height:88px}
.logo{font:italic 900 30px Arial;letter-spacing:-1.6px;color:var(--blue)}.logo small{display:block;font:9px Arial;letter-spacing:3px;color:var(--muted);margin-top:-3px}
nav{display:flex;gap:32px;font-size:14px}nav a.active{color:var(--blue)}
.hero{background:linear-gradient(110deg,#f3f7fa 55%,#e5eef5);overflow:hidden}
.hero .wrap{position:relative;display:grid;grid-template-columns:1.2fr 1fr;align-items:center;min-height:335px;padding-top:35px;padding-bottom:68px}
.eyebrow{color:var(--blue);font:700 11px 'Kanit',sans-serif;letter-spacing:2.4px}
.hero h1{font-size:39px;letter-spacing:-1px;line-height:1.4;margin:13px 0}.hero p{color:var(--muted);font-size:15px;max-width:510px}
.art{height:230px;position:relative;background:radial-gradient(ellipse,#cedde8,transparent 66%)}.art svg{width:100%;height:100%;filter:drop-shadow(0 20px 13px #7890a345)}
.searchbox{position:relative;margin-top:-36px;background:white;border:1px solid var(--line);border-radius:8px;box-shadow:0 14px 35px #1939520c;padding:24px 26px 25px}
.fields{display:grid;grid-template-columns:1fr 150px;gap:14px}
label{font-size:12px;color:#586c7c}
select,input{display:block;background:white;border:1px solid #ccd7e0;border-radius:4px;padding:11px;width:100%;color:var(--ink);margin-top:5px;min-width:0}
.primary{background:var(--blue);color:white;border-radius:4px;padding:12px 22px;align-self:end}
.hint{font-size:11px;color:var(--muted);margin:11px 0 0}
.section{padding:48px 0}.sectionhead{margin-bottom:23px}.sectionhead h2{font-size:23px;margin:0}.sectionhead p{margin:4px 0 0;color:var(--muted);font-size:13px}
.textlink{color:var(--blue);font-size:13px;background:transparent}
.cats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.cat{display:flex;text-align:left;align-items:center;gap:16px;background:white;border:1px solid var(--line);padding:20px;border-radius:6px;color:var(--ink)}
.cat:hover,.card:hover{border-color:#86acd0;box-shadow:0 8px 24px #1438560a}
.cat svg{width:56px;height:50px;flex:none}.cat strong{display:block;font-size:14px}.cat small{font-size:10px;color:var(--muted);letter-spacing:.8px}
.gray{background:var(--bg)}
.products{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.card{border:1px solid var(--line);border-radius:6px;overflow:hidden;background:white;display:flex;flex-direction:column}
.productimg{position:relative;height:170px;background:radial-gradient(ellipse,#e4eaf0,#f6f8fa);display:flex;align-items:center;justify-content:center}
.productimg svg{width:70%;height:130px;filter:drop-shadow(0 12px 8px #17385225)}
.badge{position:absolute;top:15px;left:16px;font:10px 'Kanit',sans-serif;letter-spacing:1.5px;color:var(--blue)}
.cardbody{padding:20px;display:flex;flex-direction:column;flex:1}
.code{font:11px monospace;color:var(--muted)}
.card h3{font-size:16px;margin:9px 0}.card p{font-size:12px;color:var(--muted);margin:5px 0 16px}
.cardbutton{margin-top:auto;border-top:1px solid var(--line);padding-top:13px;display:block;color:var(--blue);background:white;width:100%;text-align:left;font-size:13px}
footer{background:var(--navy);color:#b6c6d5;padding:32px 0;font-size:12px}
.footgrid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:35px}footer .logo{color:white;font-size:25px}footer a{display:block;margin:7px 0}
.note{padding-top:25px;margin-top:20px;border-top:1px solid #ffffff20;font-size:10px;letter-spacing:.5px}
.crumb{font-size:12px;color:var(--muted);margin:24px 0}
.listing{display:grid;grid-template-columns:230px 1fr;gap:30px}
.filter{border:1px solid var(--line);padding:18px;border-radius:6px;height:fit-content}
.filter h3{font-size:15px;margin:0 0 8px}.filter a{display:block;padding:8px 0;color:var(--muted);font-size:13px}.filter a.on{color:var(--blue);font-weight:600}
.detailgrid{display:grid;grid-template-columns:1fr 1fr;gap:50px;margin:30px 0}
.detailgrid .productimg{height:340px}.detailgrid .productimg svg{height:240px}
.detailgrid h1{font-size:28px;margin:8px 0}
.pill{display:inline-block;background:#edf4fa;color:var(--blue);padding:4px 12px;border-radius:3px;font-size:11px}
table{width:100%;border-collapse:collapse;font-size:13px}td,th{text-align:left;padding:12px 13px;border-bottom:1px solid var(--line)}th{background:var(--bg)}
.notice{background:#fff7e7;border-left:3px solid #c99128;padding:12px 16px;font-size:12px;color:#7b632d;margin:22px 0}
.empty{padding:60px;background:var(--bg);text-align:center;border-radius:6px}
.pager{display:flex;gap:6px;flex-wrap:wrap;margin-top:28px;justify-content:center}
.pager a,.pager span{padding:8px 13px;border:1px solid var(--line);border-radius:4px;font-size:13px;color:var(--ink)}
.pager .cur{background:var(--blue);color:#fff;border-color:var(--blue)}
.pager .disabled{color:#b6c3cd}
@media(max-width:800px){.wrap{padding:0 20px}nav{gap:15px;font-size:12px}.hero .wrap{grid-template-columns:1fr;min-height:auto;padding-bottom:55px}.hero h1{font-size:30px}.art{display:none}.cats{grid-template-columns:1fr 1fr}.products{grid-template-columns:1fr 1fr}.listing{grid-template-columns:1fr}.filter{display:flex;flex-wrap:wrap;gap:6px 14px}.filter h3{width:100%}.detailgrid{grid-template-columns:1fr;gap:16px}.footgrid{grid-template-columns:1fr 1fr}}
@media(max-width:520px){header .wrap{height:72px}nav a:nth-child(n+2){display:none}.logo{font-size:24px}.fields{grid-template-columns:1fr}.products,.cats{grid-template-columns:1fr}.footgrid{grid-template-columns:1fr}}
</style>
</head>
<body>
@include('catalog._svg')
<div class="utility"><div class="wrap"><span>FORMULA / AUTOMOTIVE AIR CONDITIONING</span><span>ONLINE CATALOGUE</span></div></div>
<header><div class="wrap">
  <a class="logo" href="{{ route('catalog.home') }}" aria-label="Formula หน้าหลัก">FORMULA<small>AUTOMOTIVE AIR CONDITIONING</small></a>
  <nav>
    <a href="{{ route('catalog.products') }}">แคตตาล็อกสินค้า</a>
    <a href="{{ route('catalog.home') }}#categories">หมวดหมู่สินค้า</a>
    <a href="{{ route('catalog.home') }}#contact">ติดต่อเรา</a>
  </nav>
  <span></span>
</div></header>
<main>
@yield('content')
</main>
<section class="section" id="contact" style="border-top:1px solid var(--line)"><div class="wrap">
  <div class="sectionhead"><div><h2>ต้องการตรวจสอบอะไหล่ที่เหมาะกับรถ?</h2>
  <p>เตรียมรหัสสินค้าเพื่อให้ทีมงานตรวจสอบเพิ่มเติม — ช่องทางติดต่อจะเพิ่มในขั้นถัดไป</p></div></div>
</div></section>
<footer><div class="wrap"><div class="footgrid">
  <div><div class="logo">FORMULA</div><p>อะไหล่ระบบปรับอากาศรถยนต์<br>ค้นหาข้อมูลสินค้าได้อย่างเป็นระบบ</p></div>
  <div><strong>แคตตาล็อก</strong><a href="{{ route('catalog.products') }}">สินค้าทั้งหมด</a><a href="{{ route('catalog.home') }}#categories">หมวดหมู่สินค้า</a></div>
  <div><strong>ข้อมูลบริษัท</strong><a href="{{ route('catalog.home') }}#contact">ติดต่อเรา</a></div>
</div></div></footer>
</body>
</html>
