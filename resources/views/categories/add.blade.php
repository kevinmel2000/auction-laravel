<div class="row">
    <div class="col-md-12">
        {!! Form::open([
            'route' => array('categories.store'),
            'id' => 'add-categories-form',
            'data-model' => 'DM_categories',
            'class' => 'form-horizontal',
            'role' => 'form'
        ]) !!}
            <div class="form-group">
                {!! Form::label(
                    'name',
                    trans('adminCategories.categories_name_label'),
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