                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="{{$id}}">{{$label}}</label>
                                <textarea class="ckeditor" name="{{$name}}" id="{{$id}}" data-parsley-required-message = "จำเป็นต้องระบุข้อมูลนี้" {{(isset($required_field[$name])&&($required_field[$name]=='T')) ? 'required':''}}></textarea>
                            </div>
                        </div>