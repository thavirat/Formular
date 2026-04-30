{{-- ชั่วโมง / นาที สำหรับรวมกับ payment_date เป็น datetime --}}
<div class="col-md-3 col-sm-6">
    <div class="form-group">
        <label for="{{ $hourId }}">ชั่วโมง</label>
        <select name="payment_time_hour" id="{{ $hourId }}" class="form-control">
            @for ($h = 0; $h < 24; $h++)
                <option value="{{ $h }}">{{ sprintf('%02d', $h) }}</option>
            @endfor
        </select>
    </div>
</div>
<div class="col-md-3 col-sm-6">
    <div class="form-group">
        <label for="{{ $minuteId }}">นาที</label>
        <select name="payment_time_minute" id="{{ $minuteId }}" class="form-control">
            @for ($m = 0; $m < 60; $m++)
                <option value="{{ $m }}">{{ sprintf('%02d', $m) }}</option>
            @endfor
        </select>
    </div>
</div>
