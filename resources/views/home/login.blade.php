<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger hidden" id="error_msg">
            <button class="close" data-close="alert"></button>
            <span>{{ Lang::get('homeIndex.login_error') }}</span>
        </div>
        <div class="alert alert-warning hidden" id="verify_msg">
            <button class="close" data-close="alert"></button>
            <span>{{ Lang::get('homeIndex.login_verify') }}</span>
        </div>
        {!! Form::model(
            $user,
            array(
                'route' => array('login'),
                'id' => 'login-form',
                'data-model' => 'DM_login'
            )
        ) !!}
            <div class="form-group">
                {!! Form::label(
                    'email',
                    Lang::get('homeIndex.login_email_label')
                ) !!}
                {!! Form::text(
                    'email',
                    '',
                    [
                        'class' => 'form-control input-sm',
                        'autocomplete' => 'on'
                    ]
                ) !!}
            </div>
            <div class="form-group">
                {!! Form::label(
                    'password',
                    Lang::get('homeIndex.login_password_label')
                ) !!}
                {!! Form::password(
                    'password',
                    [
                        'class' => 'form-control input-sm',
                        'autocomplete' => 'on'
                    ]
                ) !!}
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="checkbox remember">
                                <label>
                                    {!! Form::checkbox('remember', '') !!} {{ trans('homeIndex.login_remember_me') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a class="pull-right forgot-password">{{ trans('homeIndex.login_forgot_password') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <button type="submit" class="btn btn-default btn-lg ">{{ trans('homeIndex.login_sing_in') }}</button>
                </div>
            </div>
            <div class="form-group">
                <span class="vk-link">Или войдите через: <img src="{{ asset('assets/img/vk.png') }}"></span>
            </div>
            <img class="login-bg" src="{{ asset('assets/img/login-bg.png') }}">
        {!! Form::close() !!}
    </div>
</div>