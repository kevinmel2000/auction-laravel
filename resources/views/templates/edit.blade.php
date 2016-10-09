<div class="row">
    <div class="col-md-12">
        {!! Form::model(
            $template,
            [
                'route' => ['templates.update', $template->id],
                'id' => 'edit-templates-form',
                'data-model' => 'DM_templates',
                'class' => 'form-horizontal',
                'role' => 'form',
                'method' => 'PUT'
            ]
        ) !!}
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
                    $template->name,
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
                {!! Form::textArea(
                    'description',
                    $template->description,
                    [
                        'class' => 'form-control input-sm',
                        'autocomplete' => 'off'
                    ]
                ) !!}
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
                        @foreach($template->photos as $photo)
                            <div class="item">
                                <span id="remove-photo" data-id="{{ $photo->id }}" class="glyphicon glyphicon-remove"></span>
                                <div class="canvas">
                                    <img src="{{ asset('assets/templates/photos/' . $photo->name) }}" style="height: 146px;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <input type="file" id="photo_upload" class="hidden">
            <input type="hidden" id="deleted_photo">
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