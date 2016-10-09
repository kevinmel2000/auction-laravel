@extends('layouts.admin')
@section('title', trans('adminProducts.products_page_title'))

@section('content')
    <div class="row table-header">
        <div class="col-md-12">
            <button class="btn btn-success pull-right" id="add_products">{{ trans('adminProducts.products_add_names_button') }}</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table id="products_table" class="table table-striped table-hover table-condensed" data-url="{{ route('products.table', [], false) }}">
                <thead>
                    <tr>
                        <th>
                            Id
                        </th>
                        <th>
                            Start Date
                        </th>
                        <th>
                            Type
                        </th>
                        <th>
                            Category
                        </th>
                        <th>
                            Source
                        </th>
                        <th>
                            Created
                        </th>
                        <th>
                            Modified
                        </th>
                        <th style="width: 25%;">
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@push('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap-datetimepicker.min.css') }}" type="text/css" >
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
@endpush