@extends('admin.layouts.default')
@section('css')
@endsection
@section('body')
    <div class="page-content container container-plus">

        <div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card dcard" style="height:60vh;" >
                        <div class="card-body p-0">
                            <div class="row h-100 justify-content-center align-items-center">
                                <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <span class="fas fa-times-circle" style="color:red;font-size:10rem !important;"></span>
                                        <br>
                                        <h1 class="mt-4">ขออภัย</h1>
                                        <h3 class="mt-3"> คุณไม่มีสิทธิ์เข้าถึง </h3>
                                        <br>
                                        <a href="{{ url('admin') }}" class="btn btn-primary" >กลับหน้าหลัก</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div><!-- /.col -->
            </div>
        </div>


    </div>
@endsection
@section('js')
@endsection
