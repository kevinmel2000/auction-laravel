@extends('layouts.admin')
@section('title', trans('adminCategories.categories_page_title'))

@section('content')
    <div class="row table-header">
        <div class="col-md-12">
            <button class="btn btn-success pull-right" id="add_categories">{{ trans('adminCategories.categories_add_names_button') }}</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table id="categories_table" class="table table-striped table-hover table-condensed" data-url="{{ route('categories.table', [], false) }}">
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
                    <th style="width: 30%;">
                        Action
                    </th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@stop