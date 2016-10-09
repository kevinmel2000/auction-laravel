<div class="row">
    <div class="col-md-12">
        {!! Form::model(
            $name,
            [
                'route' =>['names.update', $name->id],
                'id' => 'edit-names-form',
                'data-model' => 'DM_names',
                'class' => 'form-horizontal',
                'role' => 'form',
                'method' => 'PUT'
            ]
        ) !!}
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
                        $name->name,
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