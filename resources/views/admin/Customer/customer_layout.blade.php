@extends('admin.layouts.default')

@section('title', $currentMenu->title)

@section('body')
<div class="page-content container container-plus">
    <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
        <h1 class="page-title text-primary-d2 text-140">
            {{__('Customer Management')}} : <span class="text-secondary-d3 text-120">{{ $customer->company_name ?? 'Customer Name' }}</span>
        </h1>

        <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
            <div class="btn-group">
                <a href="{{ url('admin/'.$lang.'/Customer/'.$customer->id.'/Contact') }}" class="btn btn-outline-info btn-bold px-4 {{$page=='contact' ? 'active':''}}">
                    <i class="fa fa-file-invoice mr-1"></i> {{__('Contact')}}
                </a>
                {{-- <a href="{{ url('admin/'.$lang.'/Customer/'.$customer->id.'/Quotation') }}" class="btn btn-outline-info btn-bold px-4">
                    <i class="fa fa-file-invoice mr-1"></i> {{__('Quotation')}}
                </a> --}}
                <a href="{{ url('admin/'.$lang.'/Customer/'.$customer->id.'/Product') }}" class="btn btn-outline-purple btn-bold px-4 {{$page=='product' ? 'active':''}}">
                    <i class="fa fa-box mr-1"></i> {{__('Product')}}
                </a>
                <a href="{{ url('admin/Customer') }}" class="btn btn-outline-lightgrey btn-bold px-4">
                    <i class="fa fa-arrow-left mr-1"></i> {{__('Back')}}
                </a>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-md-4">
            <div class="card dcard h-100">
                <div class="card-header bg-transparent border-b-1 brc-grey-l3">
                    <h5 class="card-title text-110 text-primary-d2">
                        <i class="fa fa-info-circle text-grey-m2 mr-1"></i> {{__('Customer Info')}}
                    </h5>
                </div>
                <div class="card-body bgc-white p-3">
                    <div class="mb-3">
                        <label class="text-80 text-grey-m1 uppercase font-bolder">{{__('Company Name')}}</label>
                        <div class="text-110 text-dark-m3">{{ $customer->company_name }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-80 text-grey-m1 uppercase font-bolder">{{__('Contact Name')}}</label>
                        <div class="text-110 text-dark-m3">{{ $customer->contact_name }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-80 text-grey-m1 uppercase font-bolder">{{__('Email')}} / {{__('Phone')}}</label>
                        <div class="text-110 text-dark-m3">
                            <i class="fa fa-envelope mr-1 text-grey"></i> {{ $customer->email }}<br>
                            <i class="fa fa-phone mr-1 text-grey"></i> {{ $customer->phone }}
                        </div>
                    </div>
                    <hr class="brc-grey-l3">
                    <div class="mb-2">
                        <label class="text-80 text-grey-m1 uppercase font-bolder">{{__('Address')}}</label>
                        <div class="text-95 text-dark-m3">{{ $customer->address }}</div>
                    </div>
                </div>
            </div>
        </div>

        @yield('content')
    </div>
</div>
@endsection
