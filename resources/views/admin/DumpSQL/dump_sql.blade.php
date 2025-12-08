@extends('admin.layouts.default')
@section('css')
@endsection
@section('body')
    <div class="page-content container container-plus">
        <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
            <h1 class="page-title text-primary-d2 text-140"> Dump SQL </h1>
            <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">

            </div>
        </div>

        <form id="FormDump" method="POST" src="{{ url('admin/DumpSQL') }}" >
            @csrf
            <div class="row mt-3">
                <div class="col-12">

                    <div class="card dcard">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12 p-5">

                                    <div class="form-group row">
                                        <div class="col-sm-4 col-form-label text-sm-right pr-0">
                                            <label for="id-form-field-1" class="mb-0">
                                                Password Dump SQL :
                                            </label>
                                        </div>

                                        <div class="col-sm-8">
                                            <input type="password" name="dump_password" class="form-control col-sm-8 col-md-6" placeholder="กรุณาระบุ" >
                                        </div>
                                    </div>

                                    <div class="text-center mt-5">
                                        <button type="submit" class="btn btn-success"> <i class="fa fa-download" ></i> Dump SQL</button>
                                    </div>

                                </div>
                            </div>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div><!-- /.col -->
            </div>

        </form>

@endsection
@section('js')
<script type="text/javascript">

    $(function(){
        @if ( \Session::get('error') )
            Swal.fire('Dump SQL', '{{\Session::get('error')}}', 'error');
        @endif
    })


</script>
@endsection
