<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success hidden" id="success_msg">
            <button class="close" data-close="alert"></button>
            <span>{{ Lang::get('homeIndex.registration_success') }}</span>
        </div>
        <div class="alert alert-danger hidden" id="error_msg">
            <button class="close" data-close="alert"></button>
            <span>{{ Lang::get('homeIndex.registration_error') }}</span>
        </div>
        {!! Form::model(
            $user,
            array(
                'route' => array('registration'),
                'id' => 'register-form',
                'data-model' => 'DM_registration'
            )
        ) !!}
            <div class="form-group">
                {!! Form::label(
                    'email',
                    Lang::get('homeIndex.registration_email_label')
                ) !!}
                {!! Form::text(
                    'email',
                    '',
                    [
                        'class' => 'form-control input-sm',
                        'autocomplete' => 'off'
                    ]
                ) !!}
            </div>
            <div class="form-group">
                {!! Form::label(
                    'password',
                    Lang::get('homeIndex.registration_password_label')
                ) !!}
                {!! Form::password(
                    'password',
                    [
                        'class' => 'form-control input-sm',
                        'autocomplete' => 'off'
                    ]
                ) !!}
            </div>
            <div class="form-group">
                {!! Form::label(
                    'name',
                    Lang::get('homeIndex.registration_username_label')
                ) !!}
                {!! Form::text(
                    'name',
                    '',
                    [
                        'class' => 'form-control input-sm',
                        'autocomplete' => 'off'
                    ]
                ) !!}
            </div>
            <div class="form-group">
                {!! Form::label(
                    'promoCode',
                    Lang::get('homeIndex.registration_promo_code_label')
                ) !!}
                {!! Form::text(
                    'promoCode',
                    '',
                    [
                        'class' => 'form-control input-sm',
                        'autocomplete' => 'off'
                    ]
                ) !!}
            </div>
            <div class="form-group">
                <span class="vk-link">Или войдите через: <img src="{{ asset('assets/img/vk.png') }}"></span>
            </div>
            <div class="checkbox">
                <label>
                    {!! Form::checkbox('toa', '') !!} {{ trans('homeIndex.registration_toa_label') }}
                </label>
            </div>
            <div class="checkbox">
                <label>
                    {!! Form::checkbox('pda', '') !!} {{ trans('homeIndex.registration_pda_label') }}
                </label>
            </div>
            <button type="submit" class="btn btn-default registration">{{ trans('homeIndex.registration_submit') }}</button>
        {!! Form::close() !!}
    </div>
</div>