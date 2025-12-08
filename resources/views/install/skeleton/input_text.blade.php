


                    <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="{{$id}}">{{$label}}</label>
                                    <input type="text" name="{{$name}}" id="{{$id}}" class="form-control {{$index_show==0? 'autofocus':''}}" {!!(isset($required_field[$name])&&($required_field[$name]=='T')) ? 'data-parsley-required-message="จำเป็นต้องระบุข้อมูลนี้" required':''!!}>
                                </div>
                            </div>

