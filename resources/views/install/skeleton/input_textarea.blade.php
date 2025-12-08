                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="{{$id}}">{{$label}}</label>
                                <textarea name="{{$name}}" id="{{$id}}" rows="5" class="form-control {{$index_show==0? 'autofocus':''}}" {!!(isset($required_field[$name])&&($required_field[$name]=='T')) ? 'data-parsley-required-message="จำเป็นต้องระบุข้อมูลนี้" required':''!!}></textarea>
                            </div>
                        </div>