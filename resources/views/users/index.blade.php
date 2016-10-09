@extends('layouts.admin')
@section('title', trans('adminUsers.page_title'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <table id="users_table" class="table table-striped table-hover table-condensed" data-url="{{ route('user.table', [], false) }}">
                <thead>
                    <tr>
                        <th>
                            Id
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Email
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