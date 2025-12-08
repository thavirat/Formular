                        <div class="col-md-6 col-sm-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="{{$name}}" value="{{$checkbox[0]['value']}}" id="{{$id}}" {!!(isset($required_field[$name])&&($required_field[$name]=='T')) ? 'data-parsley-required-message = "จำเป็นต้องระบุข้อมูลนี้" required':''!!}>
                                <label class="custom-control-label" for="{{$id}}" value="{{$checkbox[0]['value']}}">{{$label}}</label>
                            </div>
                        </div>