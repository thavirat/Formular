                        <div class="col-md-6 col-sm-12">
                            <label for="">{{$label}}</label>
                            @foreach($radio as $k=>$r)
<div class="custom-control custom-radio mb-1">
                                <input type="radio" id="{{$id.'_'.$r['value']}}" name="{{$name}}" value="{{$r['value']}}" class="custom-control-input" {!!(isset($required_field[$name])&&($required_field[$name]=='T')) ? 'data-parsley-required-message = "จำเป็นต้องระบุข้อมูลนี้" required':''!!}>
                                <label class="custom-control-label" for="{{$id.'_'.$r['value']}}">{{$r['name']}}</label>
                            </div>
                            @endforeach

                        </div>

