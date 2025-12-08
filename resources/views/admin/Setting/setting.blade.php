@extends('admin.layouts.default')
@section('css')
@endsection
@section('body')
   <div class="page-content container container-plus">
      <div class="page-header mb-2 pb-2 flex-column flex-sm-row align-items-start align-items-sm-center py-25 px-1">
         <h1 class="page-title text-primary-d2 text-140"> {{ $currentMenu->title }} </h1>
         <div class="page-tools mt-3 mt-sm-0 mb-sm-n1">
            <div id="datatable_filter" class="dataTables_filter">
               <label>
                  <i class="fa fa-search pos-abs mt-2 pt-3px ml-25 text-blue-m2"></i>
                  <input type="search" class="form-control pl-45 radius-round" placeholder=" Search Employees..." aria-controls="datatable">
               </label>
               <button data-rel="tooltip" type="button" class="btn radius-round btn-outline-primary border-2 btn-sm ml-2" title="" data-original-title="Add New">
                  <i class="fa fa-plus"></i>
               </button>
            </div>
         </div>
      </div>

      <div class="card bcard h-auto">
         <div class="border-t-3 brc-blue-m2">
         </div>
      </div>
   </div>
@endsection
@section('js')
@endsection
