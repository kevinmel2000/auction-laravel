@extends('layouts.master')

@section('title', trans('profile.page_title'))

@section('content')
    <div class="row profile">
        <div class="col-md-offset-1 col-md-10">
            <div class="row bet-info">
                <div class="pull-right">
                    <a href="/packages" class="btn btn-auction">{{ trans('profile.buy_bids_button') }}</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <div class="panel panel-default avatar-panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ trans('profile.avatar_header_title') }}</h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <img id="avatar" class="center-block img-circle" src="{{ (Auth::user()->avatar == '' || empty(Auth::user()->avatar)) ? asset('assets/img/avatar.png') : (strpos(Auth::user()->avatar, 'vk') === false ? Storage::cloud()->url('avatars/' . Auth::user()->avatar) : Auth::user()->avatar) }}">
                                        <br/>
                                        <div class="btn-group">
                                            <button id="upload-photo" class="btn btn-default">{{ trans('profile.photo_upload') }}</button>
                                            <button id="add-vk-avatar" class="btn btn-default {{ empty(Auth::user()->vk_id) ? 'disabled' : '' }}">{{ trans('profile.vk_photo') }}</button>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <a class="delete-photo" id="delete-avatar">{{ trans('profile.delete_photo') }}</a>
                                            </div>
                                        </div>
                                        <div style="display: none">
                                            <input type="file" id="upload-file">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ trans('profile.my_profile') }}</h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        {!! Form::model(
                                            $user,
                                            array(
                                                'route' => array('profile.profile.update'),
                                                'id' => 'profile-form',
                                                'data-model' => 'DM_profile',
                                                'class' => 'form-horizontal',
                                                'role' => 'form',
                                                'method' => 'PUT'
                                            )
                                        ) !!}
                                            <div class="form-group">
                                                <label class="col-md-3 control-label profile-label" for="name" style="padding-top: 0">
                                                    {{ trans('profile.profile_nik_label') }}
                                                    <span>{{ trans('profile.profile_nik_label_description') }}</span>
                                                </label>
                                                <div class="col-md-6">
                                                    {!! Form::text(
                                                        'name',
                                                        $user->name,
                                                        [
                                                            'class' => 'form-control input-sm',
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'email',
                                                    trans('profile.profile_email_label'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text(
                                                        'email',
                                                        $user->email,
                                                        [
                                                            'class' => 'form-control input-sm disabled',
                                                            'autocomplete' => 'off',
                                                            'disabled'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'created_at',
                                                    trans('profile.profile_registration_date_label'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text(
                                                        'created_at',
                                                        $user->created_at->formatLocalized('%e %B %Y %H:%M:%S'),
                                                        [
                                                            'class' => 'form-control input-sm disabled',
                                                            'autocomplete' => 'off',
                                                            'disabled'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="center-block btn btn-auction">{{ trans('main.save') }}</button>
                                            </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ trans('profile.my_data') }}</h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        {!! Form::model(
                                            $user,
                                            array(
                                                'route' => array('profile.data.update'),
                                                'id' => 'profile-data-form',
                                                'data-model' => 'DM_profile_data',
                                                'class' => 'form-horizontal',
                                                'role' => 'form',
                                                'method' => 'PUT'
                                            )
                                        ) !!}
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'firstname',
                                                    trans('profile.data_first_name_label'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text(
                                                        'firstname',
                                                        $user->firstname,
                                                        [
                                                            'class' => 'form-control input-sm',
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'lastname',
                                                    trans('profile.data_last_name_label'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text(
                                                        'lastname',
                                                        $user->lastname,
                                                        [
                                                            'class' => 'form-control input-sm',
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'gender',
                                                    trans('profile.data_gender_label'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    <div class="btn-group gender-select">
                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                            <span>{{ empty($user->gender) ? '' : trans('main.gender_' . $user->gender)}}</span>
                                                            <i class="pull-right fa fa-chevron-circle-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li data-val="male">{{ trans('main.gender_male') }}</li>
                                                            <li data-val="female">{{ trans('main.gender_female') }}</li>
                                                        </ul>
                                                        <input type="hidden" name="gender" value="{{ $user->gender }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'age',
                                                    trans('profile.data_age_label'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text(
                                                        'age',
                                                        empty($user->age) ? '' : Carbon\Carbon::parse($user->age)->formatLocalized('%d/%m/%Y'),
                                                        [
                                                            'class' => 'form-control input-sm',
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="center-block btn btn-auction">{{ trans('main.save') }}</button>
                                            </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (empty(Auth::user()->id))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default avatar-panel">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">{{ trans('profile.vk_profile') }}</h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body vk-profile">
                                            <div class="vk-search">
                                                <div class="row">
                                                    <a class="btn btn-auction center-block" href="https://oauth.vk.com/authorize?client_id=5407110&display=page&response_type=code&v=5.50&state=profile&scope=email&redirect_uri={{ route('vk-login') }}">{{ trans('profile.snap_vk_account_button') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default avatar-panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ trans('profile.change_password') }}</h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        {!! Form::open(
                                            array(
                                                'route' => array('profile.password.update'),
                                                'id' => 'password-change-form',
                                                'data-model' => 'DM_password_change',
                                                'class' => 'form-horizontal',
                                                'role' => 'form',
                                                'method' => 'PUT'
                                            )
                                        ) !!}
                                            @if (Auth::user()->password != Hash::make('123456'))
                                                <div class="form-group">
                                                    {!! Form::label(
                                                        'oldPassword',
                                                        trans('profile.old_password_label'),
                                                        [
                                                            'class' => 'col-md-3 control-label profile-label'
                                                        ]
                                                    ) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::password(
                                                            'oldPassword',
                                                            [
                                                                'class' => 'form-control input-sm',
                                                                'autocomplete' => 'off'
                                                            ]
                                                        ) !!}
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'password',
                                                    trans('profile.new_password_label'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::password(
                                                        'password',
                                                        [
                                                            'class' => 'form-control input-sm',
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'password_confirmation',
                                                    trans('profile.confirm_password_label'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::password(
                                                        'password_confirmation',
                                                        [
                                                            'class' => 'form-control input-sm',
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="center-block btn btn-auction">{{ trans('main.save') }}</button>
                                            </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ trans('profile.my_address') }}</h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        {!! Form::model(
                                            $user->address,
                                            array(
                                                'route' => array('profile.address.update'),
                                                'id' => 'profile-address-form',
                                                'data-model' => 'DM_profile_address',
                                                'class' => 'form-horizontal',
                                                'role' => 'form',
                                                'method' => 'PUT'
                                            )
                                        ) !!}
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'name',
                                                    trans('profile.address_name'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text(
                                                        'name',
                                                        $user->address ? $user->address->name : '',
                                                        [
                                                            'class' => 'form-control input-sm',
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'post',
                                                    trans('profile.address_post'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text(
                                                        'post',
                                                        $user->address ? $user->address->post : '',
                                                        [
                                                            'class' => 'form-control input-sm',
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'country',
                                                    trans('profile.address_country'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    <div class="btn-group country-select">
                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                            <span>{{ empty($user->gender) ? '' : trans('main.gender_' . $user->gender)}}</span>
                                                            <i class="pull-right fa fa-chevron-circle-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @foreach($countries as $country)
                                                                <li data-val="{{ $country->id }}"><img class="flag" src="{{ asset('assets/img/flags/' . strtolower($country->code) .'.png') }}">{{ $country[config('app.locale')] }}</li>
                                                            @endforeach
                                                        </ul>
                                                        <input type="hidden" name="country" value="{{ $user->address ? $user->address->country : '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'city',
                                                    trans('profile.address_city'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text(
                                                        'city',
                                                        $user->address ? $user->address->city : '',
                                                        [
                                                            'class' => 'form-control input-sm',
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'address',
                                                    trans('profile.address_address'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text(
                                                        'address',
                                                        $user->address ? $user->address->address : '',
                                                        [
                                                            'class' => 'form-control input-sm',
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'phone',
                                                    trans('profile.address_phone'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text(
                                                        'phone',
                                                        $user->address ? $user->address->phone : '',
                                                        [
                                                            'class' => 'form-control input-sm',
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label(
                                                    'details',
                                                    trans('profile.address_details'),
                                                    [
                                                        'class' => 'col-md-3 control-label profile-label'
                                                    ]
                                                ) !!}
                                                <div class="col-md-6">
                                                    {!! Form::textarea(
                                                        'details',
                                                        $user->address ? $user->address->details : '',
                                                        [
                                                            'class' => 'form-control input-sm',
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="center-block btn btn-auction">{{ trans('main.save') }}</button>
                                            </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default avatar-panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ trans('profile.important_information') }}</h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <ul class="list">
                                            <li><a>{{ trans('profile.link_1') }}</a></li>
                                            <li><a>{{ trans('profile.link_2') }}</a></li>
                                            <li><a>{{ trans('profile.link_3') }}</a></li>
                                            <li><a>{{ trans('profile.link_4') }}</a></li>
                                            <li><a>{{ trans('profile.link_5') }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default avatar-panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">{{ trans('profile.notification_setting') }}</h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        {!! Form::model(
                                            $user->address,
                                            array(
                                                'route' => array('profile.address.update'),
                                                'id' => 'profile-notification-form',
                                                'data-model' => 'DM_profile_notification',
                                                'class' => 'form-horizontal notification',
                                                'role' => 'form',
                                                'method' => 'PUT'
                                            )
                                        ) !!}
                                            <div class="form-group notification-item">
                                                <div class="row">
                                                    <div class="col-sm-1">
                                                        <div class="check-box pull-right"></div>
                                                    </div>
                                                    <div class="col-sm-10">{{ trans('profile.notification_1') }}</div>
                                                </div>
                                                <input type="hidden">
                                            </div>
                                            <div class="form-group notification-item">
                                                <div class="row">
                                                    <div class="col-sm-1">
                                                        <div class="check-box pull-right"></div>
                                                    </div>
                                                    <div class="col-sm-10">{{ trans('profile.notification_2') }}</div>
                                                </div>
                                                <input type="hidden">
                                            </div>
                                            <div class="form-group notification-item">
                                                <div class="row">
                                                    <div class="col-sm-1">
                                                        <div class="check-box pull-right"></div>
                                                    </div>
                                                    <div class="col-sm-10">{{ trans('profile.notification_3') }}</div>
                                                </div>
                                                <input type="hidden">
                                            </div>
                                            <div class="form-group">
                                            <button type="submit" class="center-block btn btn-auction">{{ trans('main.save') }}</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/cropper.css') }}" type="text/css" >
    <link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap-datepicker.css') }}" type="text/css" >
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ URL::asset('assets/js/cropper.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrap-datepicker.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.profile #age').datepicker({
                format: 'dd/mm/yyyy',
                weekStart: 1,
                endDate: '{{ Carbon\Carbon::parse('last day of December ' . (date('Y') - 18))->formatLocalized('%d/%m/%Y') }}',
                clearBtn: true,
                language: 'ru', //todo lezun dzel
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>
@endpush