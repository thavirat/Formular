$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// ต่ออายุ session + ดึง CSRF token ชุดใหม่มาใส่หน้า (meta / header / input[name=_token])
// เรียกเป็นระยะจาก layout และเรียกซ้ำเมื่อเจอ 419 เพื่อให้ save ได้โดยไม่ต้องคีย์ใหม่
function refreshCsrfToken() {
    if (typeof url_gb === 'undefined') { return $.Deferred().reject().promise(); }
    return $.get(url_gb + '/admin/KeepAlive').done(function (res) {
        var token = (res && res.token) ? res.token : null;
        if (token) {
            $('meta[name="csrf-token"]').attr('content', token);
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': token } });
            $('input[name="_token"]').val(token);
            if (window.Laravel) { window.Laravel.csrfToken = token; }
        }
    });
}

// จัดการ 419 (CSRF token mismatch) ที่เดียว: ต่ออายุ token ใหม่ + แจ้งผู้ใช้แบบเป็นมิตร
// มี lock กันเด้งซ้ำ เผื่อทั้ง global handler และ .fail() ของหน้ายิงพร้อมกัน
var _csrf419Lock = false;
function handleCsrf419() {
    refreshCsrfToken();
    if (_csrf419Lock) { return; }
    _csrf419Lock = true;
    setTimeout(function () { _csrf419Lock = false; }, 1500);
    if (typeof Swal !== 'undefined') {
        Swal.fire(
            'เซสชันหมดอายุ',
            'ระบบต่ออายุการใช้งานให้ใหม่แล้ว กรุณากดบันทึก/ยืนยันอีกครั้ง (ข้อมูลในหน้านี้ยังอยู่ครบ ไม่ต้องกรอกใหม่)',
            'warning'
        );
    }
}

// ตัวดัก 419 แบบ global: ครอบ ajax ทุกจุดในระบบ แม้หน้าที่ไม่ได้เรียก ajaxFail
$(document).ajaxError(function (event, jqxhr, settings) {
    if (!jqxhr || jqxhr.status !== 419) { return; }
    // เว้น KeepAlive เอง กัน loop
    if (settings && settings.url && settings.url.indexOf('/admin/KeepAlive') !== -1) { return; }
    handleCsrf419();
});

$.extend( true, $.fn.dataTable.defaults, {
    "pageLength": 50,
    "oClasses": {
        "sLengthSelect": "custom-select custom-select-sm form-control form-control-sm",
        "sFilter": "float-right",
        "sFilterInput": "form-control pl-45 radius-round"
    },
    "processing": true,
    "serverSide": true,
    "language": {
        "aria": {
            "sortAscending": ": เรียงจากน้อยไปมาก",
            "sortDescending": ": เรียงจากมากไปน้อย"
        },
        "paginate": {
            "previous": "ก่อนหน้า",
            "next": "ถัดไป"
        },
        "processing": 'กรุณารอสักครู่....',
        "emptyTable": "* ไม่พบข้อมูล",
        "info": "แสดงข้อมูลรายการที่ _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
        "infoEmpty": " ",
        "infoFiltered": "(ค้นหาจาก _MAX_ รายการ)",
        "lengthMenu": "_MENU_",
        "search": '<i class="fa fa-search pos-abs mt-2 pt-3px ml-25 text-blue-m2"></i>',
        "searchPlaceholder": "ค้นหา ",
        "zeroRecords": "* ไม่พบข้อมูล"
    },
    responsive: false,
    "drawCallback": function( settings ) {
        $('.btn-tooltip').tooltip({
            placement : 'top',
            trigger : 'hover'
        });
    }
} );

$('body').on('keydown','.number-only',function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
    // Allow: Ctrl+A, Command+A
    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
    // Allow: home, end, left, right, down, up
    (e.keyCode >= 35 && e.keyCode <= 40)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});

function loadingButton(btn){
    var org_text = btn.data('loading');
    var current_text = btn.html();
    if(org_text===undefined){
        org_text = '<i class="fa fa-spinner fa-spin"></i>';
    }
    btn.attr('disabled','disabled');
    btn.html(org_text);
    btn.data('loading' , current_text);
}


function resetButton(btn){
    var org_text = btn.data('loading');
    var current_text = btn.html();
    if(!org_text){
        org_text = '<i class="fa fa-refresh fa-spin"></i>';
    }
    btn.removeAttr('disabled','disabled');
    btn.html(org_text);
    btn.data('loading' , current_text);
}

if($('.init-date').length>0){
    $('.init-date').datepicker({
        format : 'yyyy-mm-dd',
        minView : 2,
        startView : 0,
        autoclose : true,
        thaiyear: false,
        todayBtn: "linked",
        todayHighlight: true
    });
}

if($('.init-time').length>0){
    $('.init-time').datetimepicker({
        format: 'HH:mm'
    });
}


$('body').on('click', '[data-toggle="tooltip"]' , function () {
    $(this).tooltip('hide')
});

function convertDateToThai(data){
    var spl = data.split('-');
    return spl[2]+'/'+spl[1]+'/'+(parseInt(spl[0])+543);
}


$('.modal').on('shown.bs.modal', function() {
    $(this).find('.autofocus').trigger('focus');
  });

function ajaxFail(res, form) {
    // 419 = CSRF token mismatch (session/token หมดอายุ เพราะเปิดหน้าค้างไว้นาน)
    // ใช้ตัวจัดการกลาง (มี lock กันเด้งซ้ำกับ global ajaxError) — ข้อมูลในหน้ายังอยู่ครบ
    if (res && res.status === 419) {
        handleCsrf419();
        if (form) { resetButton(form.find('button[type=submit]')); }
        return;
    }
    try {
        var response = $.parseJSON(res.responseText);
        var str = "กรุณาถ่ายรูปหน้าจอนี้ให้กับเจ้าหน้าที่\n\r" + response.message + "\n\r" + response.exception + "\n\r" + response.file + " Line : " + response.line;
        Swal.fire('Please photo send to programmer.', str, 'error');
    } catch (e) {
        // กรณีไม่ใช่ JSON (เช่น Error 500 แบบหน้าขาว)
        Swal.fire('Error', 'Internal Server Error (500)', 'error');
    }

    if (form) {
        resetButton(form.find('button[type=submit]'));
    }
}

function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}


function addNumformat(nStr){
       nStr += '';
       x = nStr.split('.');
       x1 = x[0];
       x2 = x.length > 1 ? '.' + x[1] : '';
       var rgx = /(\d+)(\d{3})/;
       while (rgx.test(x1)) {
           x1 = x1.replace(rgx, '$1' + ',' + '$2');
       }
       return x1 + x2;
   }

function showIntegerOrFloat(number, is_number_format = false)
{
    number = parseFloat(number);
    if ( Number(number) === number && number % 1 === 0 ) {
        return ( is_number_format ? addNumformat(number) : number );
    }
    if ( Number(number) === number && number % 1 !== 0 ) {
        return ( is_number_format ? addNumformat(number.toFixed(2)) : number );
    }
    return '';
}

$('body').on('click','.remove_date_time',function(){
    $(this).prev().val('');
});


$('body').on('click', '.btn-clear-date', function() {
    var targetInput = $(this).data('target');
    $(targetInput).val('').trigger('change');
});
