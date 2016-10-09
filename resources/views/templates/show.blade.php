<div class="row">
    <div class="col-md-12">
        {!! Form::open([
            'route' => array('templates.store'),
            'id' => 'add-templates-form',
            'data-model' => 'DM_templates',
            'class' => 'form-horizontal',
            'role' => 'form'
        ]) !!}
            <div class="form-group">
                {!! Form::label(
                    'name',
                    trans('adminTemplates.templates_name_label'),
                    [
                        'class' => 'col-md-2 control-label profile-label'
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
            <div class="form-group">
                {!! Form::label(
                     'description',
                     trans('adminTemplates.templates_description_label'),
                     [
                         'class' => 'col-md-2 control-label profile-label'
                     ]
                ) !!}
                <div class="col-md-10">
                    <textarea name="description" id="description" rows="10" style="width: 100%"></textarea>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label(
                     'name',
                     trans('adminTemplates.templates_photo_label'),
                     [
                         'class' => 'col-md-2 control-label profile-label'
                     ]
                ) !!}
                <div class="col-md-10">
                    <div class="row image-container ">
                        <div class="gridly">
                            <div class="item">
                                <i class="fa fa-plus-circle" id="add_photo"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="file" id="photo_upload" class="hidden">
            </div>
            <div class="upload hidden">
                <div class="progress progress-striped active" id="uploadBar">
                    <div style="position: absolute; width: 100%">
                        <span class="center-block" style="padding-top: 2px;">0</span>
                    </div>
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>