<div class="row">
    <div class="col-md-12">
        {!! Form::model(
            $category,
            [
                'route' =>['categories.update', $category->id],
                'id' => 'edit-categories-form',
                'data-model' => 'DM_categories',
                'class' => 'form-horizontal',
                'role' => 'form',
                'method' => 'PUT'
            ]
        ) !!}
            <div class="form-group">
                {!! Form::label(
                    'name',
                    trans('adminNames.categories_name_label'),
                    [
                        'class' => 'col-md-3 control-label profile-label'
                    ]
                ) !!}
                <div class="col-md-6">
                    {!! Form::text(
                        'name',
                        $category->name,
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