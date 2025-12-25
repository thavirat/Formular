                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">

                                <label for="{{$id}}">{{$label}}</label>
                                <select name="{{$name}}" id="{{$id}}" class="form-control {{($select_type == 'select2')? 'select2': ''}} {{$index_show==0? 'autofocus':''}}" {!!(isset($required_field[$name])&&($required_field[$name]=='T')) ? 'data-parsley-required-message = "จำเป็นต้องระบุข้อมูลนี้" required':''!!} >
                                    <option value="">เลือกกรุณาเลือก</option>
                                    @@foreach(${{ucfirst(\Str::plural($select[$name]))}} as ${{$select[$name]}})
                                    <option value="{{ $<?php echo $select[$name].'->id';?> }}">{{ $<?php echo $select[$name].'->'.$select_field[$name];?> }}</option>
                                    @@endforeach
                                </select>
                            </div>
                        </div>
