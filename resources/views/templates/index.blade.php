@extends('layouts.admin')
@section('title', trans('adminTemplates.templates_page_title'))

@section('content')
    <div class="row table-header">
        <div class="col-md-12">
            <button class="btn btn-success pull-right" id="add_templates">{{ trans('adminTemplates.templates_add_names_button') }}</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table id="templates_table" class="table table-striped table-hover table-condensed" data-url="{{ route('templates.table', [], false) }}">
                <thead>
                    <tr>
                        <th>
                            Id
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            Created
                        </th>
                        <th>
                            Modified
                        </th>
                        <th style="width: 30%">
                            Action
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('assets/js/wysihtml5-0.3.0.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/bootstrap-wysihtml5.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/cropper.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/jquery.gridly.js') }}" type="text/javascript"></script>
@endpush

@push('css')
    <link href="{{ asset('assets/css/bootstrap-wysihtml5.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/wysiwyg-color.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/cropper.css') }}" rel="stylesheet" type="text/css"/>
@endpush