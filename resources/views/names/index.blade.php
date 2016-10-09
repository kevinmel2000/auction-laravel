@extends('layouts.admin')
@section('title', trans('adminNames.names_page_title'))

@section('content')
    <div class="row table-header">
        <div class="col-md-12">
            <button class="btn btn-success pull-right" id="add_names">{{ trans('adminNames.names_add_names_button') }}</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table id="names_table" class="table table-striped table-hover table-condensed" data-url="{{ route('names.table', [], false) }}">
                <thead>
                <tr>
                    <th>
                        Id
                    </th>
                    <th>
                        Name
                    </th>
                    <th>
                        Created
                    </th>
                    <th>
                        Modified
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@stop