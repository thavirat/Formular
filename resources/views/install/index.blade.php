@extends('admin.layouts.default')
@section('css')

@endsection
@section('body')

    <div id="app" class="page-content container container-plus">
        <form id="frmInstall" @submit.prevent="install">
            <div class="card acard mt-2 mt-lg-3">
                <div class="card-header">
                    <h3 class="card-title text-125 text-primary-d2">
                        <i class="far fa-edit text-dark-l3 mr-1"></i>
                        Install
                    </h3>
                </div>

                <div class="card-body px-3 pb-1">

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="model_name" class="mb-0">Model Name</label>
                            <input type="text" class="form-control form-control-sm" id="model_name" name="model_name"
                                v-model="model_name" v-on:change="genRelevant">
                            <small class="form-text text-muted">Ex. tb_provinces ตั้งเป็น Province, tb_product_types
                                ตั้งเป็น ProductType</small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="controller_name" class="mb-0">Controller Name</label>
                            <input type="text" class="form-control form-control-sm" id="controller_name"
                                name="controller_name" v-model="controller_name">
                            <small class="form-text text-muted">Ex. ProvinceController</small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="view_name" class="mb-0">View Name</label>
                            <input type="text" class="form-control form-control-sm" id="view_name" name="view_name"
                                v-model="view_name">
                            <small class="form-text text-muted">Ex. province , admin_user</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="menu_name" class="mb-0">Menu Name</label>
                            <input type="text" class="form-control form-control-sm" id="menu_name" name="menu_name" v-model="menu_name">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="modal_size" class="mb-0">Modal Size</label>
                            <select class="form-control form-control-sm" name="modal_size" id="modal_size">
                                <option value="">เล็ก</option>
                                <option value="modal-lg" selected>กลาง</option>
                                <option value="modal-xl">ใหญ่</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="table_name" class="mb-0">เลือกตาราง</label>
                            <select class="form-control form-control-sm" name="table_name" id="table_name"
                                v-on:change="getField($event)">
                                <option>กรุณาเลือกตาราง</option>
                                @foreach ($Tables as $i => $Table)
                                    <option value="{{ $Table->{'Tables_in_' . env('DB_DATABASE')} }}">
                                        {{ $Table->{'Tables_in_' . env('DB_DATABASE')} }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-4">

                        <div class="col-lg-8">
                            <div class="card h-100">
                                <div class="card-header">
                                    <span class="card-title text-125">
                                        Form Add/Edit
                                    </span>
                                </div>
                                <div class="card-body" v-for="(v, index) in fields" v-bind:key="v.Field">

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>
                                                <input type="checkbox" class="ace-switch input-sm"
                                                    :name="`field_in_form[${v . Field}]`" v-model="v.field_in_form" v-on:click="changeFiledIntable(index)">
                                                @{{ v . Field }}
                                            </label>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>
                                                <input type="checkbox" class="ace-switch input-sm"
                                                    :name="`required_field[${v . Field}]`" value="T" v-model="v.required_field">
                                                บังคับคีย์
                                            </label>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">ชื่อ</span>
                                                </div>
                                                <input type="text" class="form-control" 
                                                    :name="`name_in_form[${v . Field}]`" v-model="v.name_in_form" v-on:keyup="changeNameIntable(index)">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <select :name="`input_in_form[${v.Field}]`"
                                                    v-model="v.input_in_form"
                                                    class="form-control form-control-sm select-type-input"
                                                    v-on:change="selectInput(index, $event)">
                                                    <option value="">เลือกประเภทอินพุต</option>
                                                    <option value="text">Text</option>
                                                    <option value="password">รหัสผ่าน</option>
                                                    <option value="radio">Radio</option>
                                                    <option value="checkbox">Checkbox</option>
                                                    <option value="textarea">Text Area</option>
                                                    <option value="editor">CK Editor</option>
                                                    <option value="date">Date Picker</option>
                                                    <option value="number">Number</option>
                                                    <option value="dropdown">DropDown</option>
                                                    <option value="file">Upload File</option>
                                                    <option value="OrakSingle">Upload Photo</option>
                                                </select>
                                            </div>

                                            {{-- @{{ v.data_checkbox }} --}}
                                            <div class="row"
                                                v-for="(checkbox , checkbox_index) in v.data_checkbox"
                                                :key="checkbox_index">
                                                <div class="col-sm-12">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">@{{ checkbox . name }}</span>
                                                        </div>
                                                        <input type="text" class="form-control"
                                                            :name="`checkbox[${v.Field}][${checkbox_index}][value]`"
                                                            placeholder="Here's value">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- @{{ v.data_radio }} --}}
                                            <div class="form-row" v-for="(radio , radio_index) in v.data_radio"
                                                v-bind:key="radio_index">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">ชื่อ</span>
                                                        </div>
                                                        <input type="text" class="form-control"
                                                            :name="`radio[${v.Field}][${radio_index}][name]`"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">ค่า</span>
                                                        </div>
                                                        <input type="text" class="form-control"
                                                            v-bind:name="`radio[${v.Field}][${radio_index}][value]`"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Select --}}
                                            <div class="row" v-if="v.select_open">
                                                <div class="col-sm-12">
                                                    <select :name="`select[${v.Field}]`"
                                                    class="form-control form-control-sm select-type-input"
                                                    v-on:change="select_dropdown(index, $event)">
                                                    <option value="">เลือก Model</option>
											        <option v-for="(model , model_index) in models" v-bind:value="model.substr(0 , model.length-4)">@{{model.substr(0 , model.length-4)}}</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select :name="`select_field[${v.Field}]`"
                                                    class="form-control form-control-sm select-type-input">
                                                    <option value="">เลือก Field</option>
											        <option v-for="(field , field_index) in v.list_select_fields" v-bind:value="field.Field">@{{field.Field}}</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select :name="`select_type[${v.Field}]`" class="form-control form-control-sm select-type-input">
                                                        <option value="">เลือกประเภท Dropdown</option>
                                                        <option value="">ธรรมดา</option>
                                                        <option value="select2">Select2</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.card -->
                        </div>

                        <div class="col-lg-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <span class="card-title text-125">
                                        Datatable
                                    </span>
                                </div>
                                <div class="card-body" v-for="v in fields" :key="v.Field">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label>
                                                <input type="checkbox" class="ace-switch input-sm"
                                                    :name="`field_in_table[${v . Field}]`" v-model="v.field_in_table">
                                                @{{ v . Field }}
                                            </label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">ชื่อ</span>
                                                </div>
                                                <input type="text" class="form-control"
                                                    :name="`name_in_table[${v . Field}]`" v-model="v.name_in_table">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.card -->
                        </div>

                    </div>

                </div><!-- /.card-body -->

            </div>
            <div class="row justify-content-md-center mt-3">
                <div class="col-sm-4 text-center">
                    <button type="submit" class="btn btn-success">Install</button>
                </div>
            </div>
        </form>
    </div>

@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.2.1/vue.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>
        var app = new Vue({
            el: "#app",
            created() {
                this.getModel();
            },
            data: function() {
                return {
                    model_name: null,
                    controller_name: null,
                    view_name: null,
                    menu_name: null,
                    fields: {},
                    models: [],
                };
            },
            methods: {
                getModel() {
                    fetch(`{{ url('admin/Install/getModel') }}`, {
                        method: 'GET'
                    }).then(res => res.json()).then(data => {
                        this.models = data;
                    }).catch(err => console.log(err));
                },
                genRelevant() {
                    this.genControllerName();
                    this.genViewName();
                    this.genMenuName();
                    this.autoSelectTable();
                },
                genControllerName() {
                    this.controller_name = `${this.model_name}Controller`;
                },
                genMenuName() {
                    this.menu_name = this.model_name;
                },
                genViewName() {
                    var txt = this.model_name.split(/(?=[A-Z])/);
                    txt = txt.join("_").toString();
                    txt = txt.toLowerCase();
                    this.view_name = txt;
                },
                autoSelectTable(){
                    var tb_name = this.model_name.split(/(?=[A-Z])/);
                    tb_name = tb_name.join("_").toString();
                    tb_name = tb_name.toLowerCase();
                    var prefix = "{{ env('DB_PREFIX') }}";
                    tb_name = `${prefix}${tb_name}s`;
                    var el = document.getElementById('table_name');
                    el.value = tb_name;
                    el.dispatchEvent(new Event("change"));
                },
                getField(event) {
                    var tb_name = event.target.value;
                    fetch(`{{ url('admin/Install/getField?name=${tb_name}') }}`, {
                        method: 'GET'
                    }).then(res => res.json()).then(data => {
                        this.fields = data;
                        this.setDefaultVariableInField();
                    }).catch(err => console.log(err));
                },
                setDefaultVariableInField(){
                    this.fields.forEach((field) =>{
                        this.$set(field, 'field_in_form', null);
                        this.$set(field, 'field_in_table', null);
                        this.$set(field, 'name_in_form', null);
                        this.$set(field, 'name_in_table', null);
                        this.$set(field, 'required_field', null);
                        this.$set(field, 'input_in_form', '');
                    })
                },
                changeFiledIntable(index){
                    // Selected field in table auto
                    this.fields[index].field_in_table = this.fields[index].field_in_form;
                },
                changeNameIntable(index){
                    // Change name in table auto
                    this.fields[index].name_in_table = this.fields[index].name_in_form;
                },
                selectInput(index, event) {
                    var val = event.target.value;
                    this.fields[index].input_in_form = val;
                    
                    this.clearAllOption(index);

                    if (val == 'checkbox') {
                        this.$set(this.fields[index], 'data_checkbox', []);
                        this.fields[index].data_checkbox[0] = {
                            'name': 'เช็ค',
                            'value': '',
                        };
                        this.fields[index].data_checkbox[1] = {
                            'name': 'ไม่เช็ค',
                            'value': '',
                        };
                    } else if (val == 'radio') {
                        this.$set(this.fields[index], 'data_radio', []);
                        this.fields[index].data_radio[0] = {
                            'name': 'เลือก',
                            'value': '',
                        };
                        this.fields[index].data_radio[1] = {
                            'name': 'ไม่เลือก',
                            'value': '',
                        };
                    } else if (val == 'dropdown') {
                        this.$set(this.fields[index], 'select_open', false);
                        this.fields[index].select_open = true;
                    }
                },
                select_dropdown(index, event) {
                    var model = event.target.value;
                    this.$set(this.fields[index], 'list_select_fields', {});
                    fetch(`{{ url('admin/Install/GetFieldFromModel?model=${model}') }}`, {
                        method: 'GET'
                    }).then(res => res.json()).then(data => {
                        this.fields[index].list_select_fields = data;
                    }).catch(err => console.log(err));
                },
                clearAllOption(index) {
                    if (this.fields[index].data_checkbox) {
                        this.fields[index].data_checkbox = [];
                    }
                    if (this.fields[index].data_radio) {
                        this.fields[index].data_radio = [];
                    }
                    if (this.fields[index].select_open) {
                        this.fields[index].select_open = false;
                    }
                },
                install() {
                    var form = document.getElementById('frmInstall');
                    var formData = new FormData(form);
                    axios.post("{{ url('admin/Install') }}", formData)
                        .then((response) => {
                            if(response.data.status == 1){
                                Swal.fire('Install', response.message, 'success');
                            }
                        }, (response) => {
                            // error callback
                        });
                }
            },
        });
        Vue.config.devtools = true;
    </script>

@endsection
