<div class="row">
    <div class="col-md-12">
        {!! Form::open([
            'route' => array('names.store'),
            'id' => 'add-names-form',
            'data-model' => 'DM_names',
            'class' => 'form-horizontal',
            'role' => 'form'
        ]) !!}
            <div class="form-group">
                {!! Form::label(
                    'name',
                    trans('adminNames.names_name_label'),
                    [
                        'class' => 'col-md-3 control-label profile-label'
                    ]
                ) !!}
                <div class="col-md-6">
                    {!! Form::text(
                        'name',
                        '',
                        [
                            'class' => 'form-control input-sm',
                            'autocomplete' => 'off'
                        ]
                    ) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>