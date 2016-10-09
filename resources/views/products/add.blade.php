<div class="row">
    <div class="col-md-12">
        {!! Form::open([
            'route' => array('products.store'),
            'id' => 'add-products-form',
            'data-model' => 'DM_products',
            'class' => 'form-horizontal',
            'role' => 'form'
        ]) !!}
            <div class="form-group">
                {!! Form::label(
                    'template_id',
                    trans('adminProducts.products_template_label'),
                    [
                        'class' => 'col-md-3 control-label profile-label'
                    ]
                ) !!}
                <div class="col-md-6">
                    {!! Form::select(
                        'template_id',
                        $templates,
                        null,
                        [
                            'class' => 'form-control'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label(
                    'category_id',
                    trans('adminProducts.products_category_label'),
                    [
                        'class' => 'col-md-3 control-label profile-label'
                    ]
                ) !!}
                <div class="col-md-6">
                    {!! Form::select(
                        'category_id',
                        $categories,
                        null,
                        [
                            'class' => 'form-control'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label(
                    'start_date',
                    trans('adminProducts.products_start_date_label'),
                    [
                        'class' => 'col-md-3 control-label profile-label'
                    ]
                ) !!}
                <div class="col-md-6">
                    {!! Form::text(
                        'start_date',
                        '',
                        [
                            'class' => 'form-control input-sm',
                            'autocomplete' => 'off'
                        ]
                    ) !!}
                    <input type="hidden" name="offset" id="offset">
                </div>
            </div>
            <div class="form-group">
                {!! Form::label(
                    'type',
                    trans('adminProducts.products_type_label'),
                    [
                        'class' => 'col-md-3 control-label profile-label'
                    ]
                ) !!}
                <div class="col-md-6">
                    {!! Form::select(
                        'type',
                        [
                            'beginning' => trans('adminProducts.products_type_beginning'),
                            'ticket' => trans('adminProducts.products_type_ticket'),
                            'common' => trans('adminProducts.products_type_common')
                        ],
                        null,
                        [
                            'class' => 'form-control'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label(
                    'source',
                    trans('adminProducts.products_source_label'),
                    [
                        'class' => 'col-md-3 control-label profile-label'
                    ]
                ) !!}
                <div class="col-md-6">
                    {!! Form::select(
                        'source',
                        [
                            'common' => trans('adminProducts.products_source_common'),
                            'game_zone' => trans('adminProducts.products_source_game_zone'),
                        ],
                        null,
                        [
                            'class' => 'form-control'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label(
                    'price',
                    trans('adminProducts.products_price_label'),
                    [
                        'class' => 'col-md-3 control-label profile-label'
                    ]
                ) !!}
                <div class="col-md-6">
                    {!! Form::text(
                        'price',
                        '',
                        [
                            'class' => 'form-control input-sm',
                            'autocomplete' => 'off'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label(
                    'coefficient',
                    trans('adminProducts.products_coefficient_label'),
                    [
                        'class' => 'col-md-3 control-label profile-label'
                    ]
                ) !!}
                <div class="col-md-6">
                    {!! Form::text(
                        'coefficient',
                        '',
                        [
                            'class' => 'form-control input-sm',
                            'autocomplete' => 'off'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label(
                    'bot_count',
                    trans('adminProducts.products_bot_count_label'),
                    [
                        'class' => 'col-md-3 control-label profile-label'
                    ]
                ) !!}
                <div class="col-md-6">
                    {!! Form::text(
                        'bot_count',
                        '',
                        [
                            'class' => 'form-control input-sm',
                            'autocomplete' => 'off'
                        ]
                    ) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label(
                    'bot_names',
                    trans('adminProducts.products_bot_names_label'),
                    [
                        'class' => 'col-md-3 control-label profile-label'
                    ]
                ) !!}
                <div class="col-md-6">
                    {!! Form::select(
                        'bot_names',
                        $names,
                        null,
                        [
                            'class' => 'form-control',
                            'multiple'
                        ]
                    ) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>