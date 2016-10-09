$(document).ready(function(){
    SystemProvider.init();
    Validation.init();
    TranslateProvider.init();
    TableProvider.init();

    $(document)
        .on('submit', 'form', formValidation)
        .on('DOMNodeInserted', 'input[name="_token"]', Validation.init)
        .on('click', '.checker span', function(e){
            var elem = $(e.target).closest('span');
            var val = elem.hasClass('checked') ? 1 : '';
            elem.find('input[type="checkbox"]').val(val);
        })
        .on('change', '[type="text"], [type="checkbox"], [type="password"], [type="hidden"]:not([name="_token"]), select', inputValidation)
        .on('click', '#registration', function(e){
            e.stopPropagation();

            SystemProvider.ajax({
                url: SystemProvider.getUrl('registrationView'),
                type: 'GET',
                success: function(data) {
                    BootstrapDialog.show({
                        cssClass: 'registration-modal',
                        title: TranslateProvider.get('registration_header'),
                        type: BootstrapDialog.TYPE_DEFAULT,
                        size: BootstrapDialog.SIZE_SMALL,
                        message: data.status == 'success' ? data.html : TranslateProvider.get('Incorrect input data'),
                        onshown: function(dialog) {
                            if (data.status == 'success') {
                                var $form = dialog.$modalBody.find('form');
                                Validation.addForm($form);

                                $form.submit(function () {
                                    SystemProvider.formSubmit($form, function(data) {
                                        if(data.status == 'success') {
                                            dialog.$modalBody.find('#success_msg').removeClass('hidden');
                                            $form.addClass('hidden');
                                            setTimeout(function(){
                                                dialog.close();
                                            }, 3000);
                                        } else {
                                            $('#error_msg').removeClass('hidden');
                                        }
                                    });
                                });

                                dialog.$modalBody.find('.vk-link').click(function (event) {
                                    event.stopPropagation();

                                    location.href = 'https://oauth.vk.com/authorize?client_id=5407110&display=page&response_type=code&v=5.50&scope=email&redirect_uri=' + window.baseUrl + SystemProvider.getUrl('vk-login');
                                })
                            }
                        }
                    });
                }
            });
        })
        .on('click', '#login', function(e){
            e.stopPropagation();

            SystemProvider.ajax({
                url: SystemProvider.getUrl('loginView'),
                type: 'GET',
                success: function(data) {
                    BootstrapDialog.show({
                        cssClass: 'registration-modal',
                        title: TranslateProvider.get('login_header'),
                        type: BootstrapDialog.TYPE_DEFAULT,
                        size: BootstrapDialog.SIZE_SMALL,
                        message: data.status == 'success' ? data.html : TranslateProvider.get('Incorrect input data'),
                        onshown: function(dialog) {
                            if (data.status == 'success') {
                                var $form = dialog.$modalBody.find('form');
                                Validation.addForm($form);
                            
                                $form.submit(function () {
                                    SystemProvider.formSubmit($form, function(data) {
                                        if(data.status == 'success') {
                                            if (data.url === undefined) {
                                                location.reload();
                                            } else {
                                                location.href = data.url;
                                            }
                                        } else if(data.status == 'error') {
                                            dialog.$modalBody.find('#error_msg').removeClass('hidden');
                                        } else if(data.status == 'verify') {
                                            dialog.$modalBody.find('#verify_msg').removeClass('hidden');
                                            setTimeout(function(){
                                                dialog.close();
                                            }, 3000);
                                        }
                                    });
                                });
                            
                                dialog.$modalBody.find('.vk-link').click(function (event) {
                                    event.stopPropagation();
                            
                                    location.href = 'https://oauth.vk.com/authorize?client_id=5407110&display=page&response_type=code&v=5.50&scope=email&redirect_uri=' + window.baseUrl + SystemProvider.getUrl('vk-login');
                                })
                            }
                        }
                    });
                }
            });
        })
        .on('click', '#upload-photo', function (e) {
            e.stopPropagation();

            $('#upload-file').click();
        })
        .on('change', '#upload-file', function (e) {
            var file = e.target.files[0];
            var types = ['jpeg', 'png'];

            if(file.size > 1024 * 1024 * 32) {
                BootstrapDialog.alert({
                    title: TranslateProvider.get('error_title'),
                    message: TranslateProvider.get('max').file.replace(':attribute', 'изображение').replace(':max', 32 * 1024),
                    type: BootstrapDialog.TYPE_DANGER,
                    buttonLabel: 'OK'
                });
            } else if(types.indexOf(file.type.replace('image/', '')) == -1) {
                BootstrapDialog.alert({
                    title: TranslateProvider.get('error_title'),
                    message: TranslateProvider.get('mimes')
                                              .replace(':attribute', 'изображение')
                                              .replace(':values', types.join(', ').toUpperCase()),
                    type: BootstrapDialog.TYPE_DANGER,
                    buttonLabel: 'OK'
                });
            } else {
                var $img, reader = new FileReader();

                reader.onload = function (event) {
                    SystemProvider.ajax({
                        url: SystemProvider.getUrl('profile.upload.create'),
                        type: 'GET',
                        success: function (data) {
                            if(data.status == 'success') {
                                BootstrapDialog.show({
                                    title: TranslateProvider.get('image_cropping_title'),
                                    type: BootstrapDialog.TYPE_DEFAULT,
                                    size: BootstrapDialog.SIZE_NORMAL,
                                    message: function () {
                                        if(data.status == 'success') {
                                            var $content = $(data.html);

                                            $content.find('img').attr('src', event.target.result);//.css('max-width', '100%').css('max-height', '400px');
                                        } else {
                                            var $content = TranslateProvider.get('Incorrect input data');
                                        }

                                        return $content;
                                    },
                                    onshown: function (dialog) {
                                        $img = dialog.$modalBody.find('img');

                                        $img.cropper({
                                            aspectRatio: 1 / 1,
                                            strict: false,
                                            guides: false,
                                            highlight: true,
                                            dragCrop: false,
                                            cropBoxMovable: true,
                                            cropBoxResizable: true,
                                            resizable: false,
                                            built: function () {
                                                $img.cropper("setCropBoxData", {width: 90, height: 90});
                                            }
                                        });
                                    },
                                    buttons: [{
                                        icon: 'fa fa-sign-out',
                                        label: TranslateProvider.get('image_cropping_cancel'),
                                        action: function (dialog) {
                                            dialog.close();
                                        }
                                    }, {
                                        icon: 'fa fa-floppy-o',
                                        cssClass: 'btn-success',
                                        label: TranslateProvider.get('image_cropping_crop'),
                                        action: function (dialog) {
                                            var d = $img.cropper('getCroppedCanvas');
                                            var data = new FormData();
                                            data.append('image', d.toDataURL('image/jpeg'));

                                            var $bar = dialog.$modalBody.find('.upload .progress-bar');
                                            var $text = dialog.$modalBody.find('.upload span');
                                            dialog.$modalBody.find('.upload').removeClass('hidden');

                                            SystemProvider.ajax({
                                                url: SystemProvider.getUrl('profile.upload.store'),
                                                data: data,
                                                contentType: false,
                                                processData: false,
                                                success: function(res) {
                                                    if (res.status == 'success') {
                                                        $('#avatar').attr('src', res.avatar);
                                                        dialog.close();
                                                    }
                                                },
                                                xhr: function() {
                                                    var xhr = $.ajaxSettings.xhr();
                                            
                                                    if(xhr.upload) {
                                                        xhr.upload.onprogress = function(x) {
                                                            var done = x.loaded;
                                                            var total = x.total;
                                                            var result = Math.round(done * 100 / total);
                                                            console.log(result);
                                            
                                                            $bar.css('width', result + '%');
                                                            $text.text(result);
                                                        }
                                                    }
                                            
                                                    return xhr;
                                                }
                                            });
                                        }
                                    }]
                                });
                            }
                        }
                    });
                };

                reader.readAsDataURL(e.target.files[0]);
            }
        })
        .on('click', '#delete-avatar', function (e) {
            e.stopPropagation();
            
            SystemProvider.ajax({
                url: SystemProvider.getUrl('profile.upload.destroy'),
                type: 'DELETE',
                success: function (data) {
                    if (data.status == 'success') {
                        $('#avatar').attr('src', data.avatar);
                    }
                }
            });
        })
        .on('click', '#add-vk-avatar', function (e) {
            e.stopPropagation();

            SystemProvider.ajax({
                url: SystemProvider.getUrl('profile.vk.avatar.store'),
                success: function (data) {
                    if (data.status == 'success') {
                        $('#avatar').attr('src', data.avatar);
                    }
                }
            });
        })
        .on('submit', '#profile-form', function(e){
            var form = $(e.target);

            if(form.find('.has-error').length == 0) {
                SystemProvider.formSubmit(form, function(data){
                    if(data.status == 'success') {
                    } else {
                    }
                });
            }
        })
        .on('click', '.gender-select .dropdown-menu li, .country-select .dropdown-menu li', function (e) {
            e.stopPropagation();

            var $elem = $(e.target).closest('li');
            var $parent = $elem.closest('div.btn-group');
            var $span = $parent.find('button span');
            var $hidden = $parent.find('input');

            $span.text($elem.text());
            $hidden.val($elem.data('val'));
            $hidden.change();

            $parent.removeClass('open');
        })
        .on('submit', '#profile-data-form', function(e){
            var form = $(e.target);

            if(form.find('.has-error').length == 0) {
                SystemProvider.formSubmit(form, function(data){
                    if(data.status == 'success') {
                    } else {
                    }
                });
            }
        })
        .on('submit', '#password-change-form', function(e){
            var form = $(e.target);

            if(form.find('.has-error').length == 0) {
                SystemProvider.formSubmit(form, function(data){
                    if(data.status == 'success') {
                    } else {
                    }
                });
            }
        })
        .on('submit', '#profile-address-form', function(e){
            var form = $(e.target);

            if(form.find('.has-error').length == 0) {
                SystemProvider.formSubmit(form, function(data){
                    if(data.status == 'success') {
                    } else {
                    }
                });
            }
        })
        .on('click', '.notification-item .check-box', function (e) {
            e.stopPropagation();

            var $elem = $(e.target).closest('.check-box');
            var $parent = $elem.closest('.notification-item');
            var $hidden = $parent.find('[type="hidden"]');

            if ($elem.hasClass('active')) {
                $elem.removeClass('active');
                $hidden.val(0)
            } else {
                $elem.addClass('active');
                $hidden.val(1)
            }
        })
        .on('click', '#add_names', function (e) {
            e.stopPropagation();

            SystemProvider.ajax({
                url: SystemProvider.getUrl('names.create'),
                type: 'GET',
                success:function(data) {
                    BootstrapDialog.show({
                        title: TranslateProvider.get('add_names_modal_title'),
                        type: BootstrapDialog.TYPE_SUCCESS,
                        size: BootstrapDialog.SIZE_NORMAL,
                        closable: false,
                        onshown: function(dialog) {
                            if (data.status == 'success') {
                                var $form = dialog.$modalBody.find('form');
                                Validation.addForm($form);
                            }
                        },
                        buttons: [{
                            icon: 'fa fa-sign-out',
                            label: TranslateProvider.get('cancel'),
                            action: function(dialog) {
                                dialog.close();
                            }
                        }, {
                            icon: 'fa fa-floppy-o',
                            cssClass: 'btn-success' + (data.status != 'success' ? 'disabled' : ''),
                            label: TranslateProvider.get('save'),
                            action: function(dialog) {
                                var $form = $('#add-names-form');
                                $form.submit();

                                if($form.find('.has-error').length == 0) {
                                    SystemProvider.formSubmit($form, function (res) {
                                        if(res.status == 'success') {
                                            TableProvider.add($('#names_table')[0], res.data);
                                            dialog.close();
                                        }
                                    });
                                }
                            }
                        }],
                        message: data.status == 'success' ? data.html : TranslateProvider.get('Incorrect input data')
                    });
                }
            })
        })
        .on('click', '.edit_names', function (e) {
            e.stopPropagation();
            var $elem = $(e.target).closest('button');

            SystemProvider.ajax({
                url: $elem.data('url'),
                type: 'GET',
                success:function(data) {
                    BootstrapDialog.show({
                        title: TranslateProvider.get('add_names_modal_title'),
                        type: BootstrapDialog.TYPE_PRIMARY,
                        size: BootstrapDialog.SIZE_NORMAL,
                        closable: false,
                        onshown: function(dialog) {
                            if (data.status == 'success') {
                                var $form = dialog.$modalBody.find('form');
                                Validation.addForm($form);
                            }
                        },
                        buttons: [{
                            icon: 'fa fa-sign-out',
                            label: TranslateProvider.get('cancel'),
                            action: function(dialog) {
                                dialog.close();
                            }
                        }, {
                            icon: 'fa fa-floppy-o',
                            cssClass: 'btn-primary' + (data.status != 'success' ? 'disabled' : ''),
                            label: TranslateProvider.get('save'),
                            action: function(dialog) {
                                var $form = $('#edit-names-form');
                                $form.submit();

                                if($form.find('.has-error').length == 0) {
                                    SystemProvider.formSubmit($form, function (res) {
                                        if(res.status == 'success') {
                                            TableProvider.update($('#names_table')[0], res.data);
                                            dialog.close();
                                        }
                                    });
                                }
                            }
                        }],
                        message: data.status == 'success' ? data.html : TranslateProvider.get('Incorrect input data')
                    });
                }
            })
        })
        .on('click', '.delete_names', function (e) {
            e.stopPropagation();
            var $elem = $(e.target).closest('button');
            var $table = $elem.closest('table');
            var $row = $elem.closest('tr');

            BootstrapDialog.show({
                title: TranslateProvider.get('delete_confirm_title'),
                type: BootstrapDialog.TYPE_DANGER,
                size: BootstrapDialog.SIZE_NORMAL,
                closable: true,
                draggable: true,
                buttons: [{
                    icon: 'fa fa-sign-out',
                    label: TranslateProvider.get('cancel'),
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    icon: 'fa fa-trash-o',
                    cssClass: 'btn-danger',
                    label: TranslateProvider.get('delete'),
                    action: function(dialog) {
                        SystemProvider.ajax({
                            url: $elem.data('url'),
                            type: 'DELETE',
                            success: function (data) {
                                if (data.status == 'success') {
                                    TableProvider.delete($table[0], $row, data.id);
                                    dialog.close();
                                }
                            }
                        });
                    }
                }],
                message: 'Warning! Drop your banana?'
            });
        })
        .on('click', '#add_categories', function (e) {
            e.stopPropagation();

            SystemProvider.ajax({
                url: SystemProvider.getUrl('categories.create'),
                type: 'GET',
                success:function(data) {
                    BootstrapDialog.show({
                        title: TranslateProvider.get('categories_add_names_modal_title'),
                        type: BootstrapDialog.TYPE_SUCCESS,
                        size: BootstrapDialog.SIZE_NORMAL,
                        closable: false,
                        onshown: function(dialog) {
                            if (data.status == 'success') {
                                var $form = dialog.$modalBody.find('form');
                                Validation.addForm($form);
                            }
                        },
                        buttons: [{
                            icon: 'fa fa-sign-out',
                            label: TranslateProvider.get('cancel'),
                            action: function(dialog) {
                                dialog.close();
                            }
                        }, {
                            icon: 'fa fa-floppy-o',
                            cssClass: 'btn-success' + (data.status != 'success' ? 'disabled' : ''),
                            label: TranslateProvider.get('save'),
                            action: function(dialog) {
                                var $form = $('#add-categories-form');
                                $form.submit();

                                if($form.find('.has-error').length == 0) {
                                    SystemProvider.formSubmit($form, function (res) {
                                        if(res.status == 'success') {
                                            TableProvider.add($('#categories_table')[0], res.data);
                                            dialog.close();
                                        }
                                    });
                                }
                            }
                        }],
                        message: data.status == 'success' ? data.html : TranslateProvider.get('Incorrect input data')
                    });
                }
            })
        })
        .on('click', '.edit_categories', function (e) {
            e.stopPropagation();
            var $elem = $(e.target).closest('button');
        
            SystemProvider.ajax({
                url: $elem.data('url'),
                type: 'GET',
                success:function(data) {
                    BootstrapDialog.show({
                        title: TranslateProvider.get('categories_add_names_modal_title'),
                        type: BootstrapDialog.TYPE_PRIMARY,
                        size: BootstrapDialog.SIZE_NORMAL,
                        closable: false,
                        onshown: function(dialog) {
                            if (data.status == 'success') {
                                var $form = dialog.$modalBody.find('form');
                                Validation.addForm($form);
                            }
                        },
                        buttons: [{
                            icon: 'fa fa-sign-out',
                            label: TranslateProvider.get('cancel'),
                            action: function(dialog) {
                                dialog.close();
                            }
                        }, {
                            icon: 'fa fa-floppy-o',
                            cssClass: 'btn-primary' + (data.status != 'success' ? 'disabled' : ''),
                            label: TranslateProvider.get('save'),
                            action: function(dialog) {
                                var $form = $('#edit-categories-form');
                                $form.submit();
        
                                if($form.find('.has-error').length == 0) {
                                    SystemProvider.formSubmit($form, function (res) {
                                        if(res.status == 'success') {
                                            TableProvider.update($('#categories_table')[0], res.data);
                                            dialog.close();
                                        }
                                    });
                                }
                            }
                        }],
                        message: data.status == 'success' ? data.html : TranslateProvider.get('Incorrect input data')
                    });
                }
            })
        })
        .on('click', '.delete_categories', function (e) {
            e.stopPropagation();
            var $elem = $(e.target).closest('button');
            var $table = $elem.closest('table');
            var $row = $elem.closest('tr');

            BootstrapDialog.show({
                title: TranslateProvider.get('categories_delete_confirm_title'),
                type: BootstrapDialog.TYPE_DANGER,
                size: BootstrapDialog.SIZE_NORMAL,
                closable: true,
                draggable: true,
                buttons: [{
                    icon: 'fa fa-sign-out',
                    label: TranslateProvider.get('cancel'),
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    icon: 'fa fa-trash-o',
                    cssClass: 'btn-danger',
                    label: TranslateProvider.get('delete'),
                    action: function(dialog) {
                        SystemProvider.ajax({
                            url: $elem.data('url'),
                            type: 'DELETE',
                            success: function (data) {
                                if (data.status == 'success') {
                                    TableProvider.delete($table[0], $row, data.id);
                                    dialog.close();
                                }
                            }
                        });
                    }
                }],
                message: 'Warning! Drop your banana?'
            });
        })
        .on('click', '#add_templates', function (e) {
            e.stopPropagation();

            SystemProvider.ajax({
                url: SystemProvider.getUrl('templates.create'),
                type: 'GET',
                success:function(data) {
                    BootstrapDialog.show({
                        title: TranslateProvider.get('templates_add_names_modal_title'),
                        type: BootstrapDialog.TYPE_SUCCESS,
                        size: BootstrapDialog.SIZE_WIDE,
                        closable: false,
                        onshown: function(dialog) {
                            if (data.status == 'success') {
                                var $form = dialog.$modalBody.find('form');
                                Validation.addForm($form);

                                dialog.$modalBody.find('#description').wysihtml5({
                                    "font-styles": true,
                                    "emphasis": true,
                                    "lists": true,
                                    "html": false,
                                    "link": false,
                                    "image": false,
                                    "color": true
                                });

                                dialog.$modalBody.find('.image-container .gridly').gridly({
                                    base: 55,
                                    gutter: 0,
                                    columns: 999
                                });

                                var $items = dialog.$modalBody.find('.image-container .item');
                                var itemHeight = $($items[0]).width();
                                $items.css('height', itemHeight);

                                dialog.$modalBody.find('#add_photo').click(function (event) {
                                    event.stopPropagation();

                                    dialog.$modalBody.find('#photo_upload').click();
                                });

                                dialog.$modalBody.on('click', '#remove-photo', function (event) {
                                    event.stopPropagation();

                                    $(event.target).closest('.item').remove();
                                });

                                dialog.$modalBody.find('#photo_upload').change(function (event) {
                                    var file = event.target.files[0];
                                    var $fileElement = $(event.target);
                                    var types = ['jpeg', 'png'];

                                    if(file.size > 1024 * 1024 * 32) {
                                        BootstrapDialog.alert({
                                            title: TranslateProvider.get('error_title'),
                                            message: TranslateProvider.get('max').file.replace(':attribute', 'изображение').replace(':max', 32 * 1024),
                                            type: BootstrapDialog.TYPE_DANGER,
                                            buttonLabel: 'OK'
                                        });
                                    } else if(types.indexOf(file.type.replace('image/', '')) == -1) {
                                        BootstrapDialog.alert({
                                            title: TranslateProvider.get('error_title'),
                                            message: TranslateProvider.get('mimes')
                                                .replace(':attribute', 'изображение')
                                                .replace(':values', types.join(', ').toUpperCase()),
                                            type: BootstrapDialog.TYPE_DANGER,
                                            buttonLabel: 'OK'
                                        });
                                    } else {
                                        var $img, reader = new FileReader();

                                        reader.onload = function (event) {
                                            SystemProvider.ajax({
                                                url: SystemProvider.getUrl('profile.upload.create'),
                                                type: 'GET',
                                                success: function (data) {
                                                    if(data.status == 'success') {
                                                        BootstrapDialog.show({
                                                            title: TranslateProvider.get('image_cropping_title'),
                                                            type: BootstrapDialog.TYPE_DEFAULT,
                                                            size: BootstrapDialog.SIZE_NORMAL,
                                                            message: function () {
                                                                if(data.status == 'success') {
                                                                    var $content = $(data.html);

                                                                    $content.find('img').attr('src', event.target.result);
                                                                } else {
                                                                    var $content = TranslateProvider.get('Incorrect input data');
                                                                }

                                                                return $content;
                                                            },
                                                            onshown: function (cropp) {
                                                                $img = cropp.$modalBody.find('img');

                                                                $img.cropper({
                                                                    aspectRatio: 3 / 4,
                                                                    strict: false,
                                                                    guides: false,
                                                                    highlight: true,
                                                                    dragCrop: false,
                                                                    cropBoxMovable: true,
                                                                    cropBoxResizable: true,
                                                                    resizable: false,
                                                                    built: function () {
                                                                        $img.cropper("setCropBoxData", {width: 240, height: 180});
                                                                    }
                                                                });
                                                            },
                                                            buttons: [{
                                                                icon: 'fa fa-sign-out',
                                                                label: TranslateProvider.get('image_cropping_cancel'),
                                                                action: function (cropp) {
                                                                    cropp.close();
                                                                }
                                                            }, {
                                                                icon: 'fa fa-floppy-o',
                                                                cssClass: 'btn-success',
                                                                label: TranslateProvider.get('image_cropping_crop'),
                                                                action: function (cropp) {
                                                                    var $item = $('<div class="item"><span id="remove-photo" class="glyphicon glyphicon-remove"></span><div class="canvas"></div></div>');
                                                                    $item.find('.canvas').html($img.cropper('getCroppedCanvas'));
                                                                    $item.find('canvas').css('height', '146px');
                                                                    dialog.$modalBody.find('.image-container .gridly').append($item).gridly();

                                                                    $fileElement.replaceWith($fileElement.clone(true));
                                                                    cropp.close();
                                                                }
                                                            }]
                                                        });
                                                    }
                                                }
                                            });
                                        };

                                        reader.readAsDataURL(event.target.files[0]);
                                    }
                                })
                            }
                        },
                        buttons: [{
                            icon: 'fa fa-sign-out',
                            label: TranslateProvider.get('cancel'),
                            action: function(dialog) {
                                dialog.close();
                            }
                        }, {
                            icon: 'fa fa-floppy-o',
                            cssClass: 'btn-success' + (data.status != 'success' ? 'disabled' : ''),
                            label: TranslateProvider.get('save'),
                            action: function(dialog) {
                                var $form = $('#add-templates-form');
                                $form.submit();

                                if($form.find('.has-error').length == 0) {
                                    var formData = new FormData();
                                    formData.append('name', $form.find('#name').val());
                                    formData.append('description', $form.find('#description').val());

                                    $form.find('canvas').each(function (index, value) {
                                        formData.append('photos[' + index + ']', value.toDataURL('image/jpeg'));
                                    });

                                    var $bar = dialog.$modalBody.find('.upload .progress-bar');
                                    var $text = dialog.$modalBody.find('.upload span');
                                    dialog.$modalBody.find('.upload').removeClass('hidden');

                                    SystemProvider.ajax({
                                        url: SystemProvider.getUrl('templates.store'),
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        success: function(res) {
                                            console.log(res)
                                            if (res.status == 'success') {
                                                TableProvider.add($('#templates_table')[0], res.data);
                                                dialog.close();
                                            }
                                        },
                                        xhr: function() {
                                            var xhr = $.ajaxSettings.xhr();

                                            if(xhr.upload) {
                                                xhr.upload.onprogress = function(x) {
                                                    var done = x.loaded;
                                                    var total = x.total;
                                                    var result = Math.round(done * 100 / total);
                                                    console.log(result);

                                                    $bar.css('width', result + '%');
                                                    $text.text(result);
                                                }
                                            }

                                            return xhr;
                                        }
                                    });
                                }
                            }
                        }],
                        message: data.status == 'success' ? data.html : TranslateProvider.get('Incorrect input data')
                    });
                }
            })
        })
        .on('click', '.show_templates', function (e) {
            e.stopPropagation();

            // SystemProvider.ajax({
            //     url: SystemProvider.getUrl('templates.show').replace('{templates}', $(e.target).closest('tr').attr('id')),
            //     type: 'GET',
            //     success:function(data) {
            //         BootstrapDialog.show({
            //             title: TranslateProvider.get('templates_show_names_modal_title'),
            //             type: BootstrapDialog.TYPE_INFO,
            //             size: BootstrapDialog.SIZE_WIDE,
            //             closable: false,
            //             onshown: function(dialog) {
            //                 if (data.status == 'success') {
            //                     dialog.$modalBody.find('.image-container .gridly').gridly({
            //                         base: 55,
            //                         gutter: 0,
            //                         columns: 999
            //                     });
            //                 }
            //             },
            //             buttons: [{
            //                 icon: 'fa fa-sign-out',
            //                 label: TranslateProvider.get('cancel'),
            //                 action: function(dialog) {
            //                     dialog.close();
            //                 }
            //             }],
            //             message: data.status == 'success' ? data.html : TranslateProvider.get('Incorrect input data')
            //         });
            //     }
            // })
        })
        .on('click', '.edit_templates', function (e) {
            e.stopPropagation();
            var $elem = $(e.target).closest('button');
        
            SystemProvider.ajax({
                url: $elem.data('url'),
                type: 'GET',
                success:function(data) {
                    BootstrapDialog.show({
                        title: TranslateProvider.get('templates_edit_names_modal_title'),
                        type: BootstrapDialog.TYPE_PRIMARY,
                        size: BootstrapDialog.SIZE_WIDE,
                        closable: false,
                        onshown: function (dialog) {
                            dialog.$modalBody.find('.image-container .gridly').gridly();
                        },
                        onshow: function(dialog) {
                            if (data.status == 'success') {
                                var $form = dialog.$modalBody.find('form');
                                Validation.addForm($form);

                                dialog.$modalBody.find('#description').wysihtml5({
                                    "font-styles": true,
                                    "emphasis": true,
                                    "lists": true,
                                    "html": false,
                                    "link": false,
                                    "image": false,
                                    "color": true
                                });

                                dialog.$modalBody.find('.image-container .gridly').gridly({
                                    base: 55,
                                    gutter: 0,
                                    columns: 999
                                });
                                

                                dialog.$modalBody.find('#add_photo').click(function (event) {
                                    event.stopPropagation();

                                    dialog.$modalBody.find('#photo_upload').click();
                                });

                                dialog.$modalBody.on('click', '#remove-photo', function (event) {
                                    event.stopPropagation();
                                    var $elem = $(event.target).closest('.glyphicon');
                                    var $parent = $elem.closest('.item');
                                    var $deleted = dialog.$modalBody.find('#deleted_photo');
                                    
                                    if ($parent.find('img').length > 0) {
                                        var ids = $deleted.val() == '' ? [] : $deleted.val().split('|');
                                        ids.push($elem.data('id'));
                                        $deleted.val(ids.join('|'))
                                    }
                                    
                                    $(event.target).closest('.item').remove();
                                });

                                dialog.$modalBody.find('#photo_upload').change(function (event) {
                                    var file = event.target.files[0];
                                    var $fileElement = $(event.target);
                                    var types = ['jpeg', 'png'];

                                    if(file.size > 1024 * 1024 * 32) {
                                        BootstrapDialog.alert({
                                            title: TranslateProvider.get('error_title'),
                                            message: TranslateProvider.get('max').file.replace(':attribute', 'изображение').replace(':max', 32 * 1024),
                                            type: BootstrapDialog.TYPE_DANGER,
                                            buttonLabel: 'OK'
                                        });
                                    } else if(types.indexOf(file.type.replace('image/', '')) == -1) {
                                        BootstrapDialog.alert({
                                            title: TranslateProvider.get('error_title'),
                                            message: TranslateProvider.get('mimes')
                                                .replace(':attribute', 'изображение')
                                                .replace(':values', types.join(', ').toUpperCase()),
                                            type: BootstrapDialog.TYPE_DANGER,
                                            buttonLabel: 'OK'
                                        });
                                    } else {
                                        var $img, reader = new FileReader();

                                        reader.onload = function (event) {
                                            SystemProvider.ajax({
                                                url: SystemProvider.getUrl('profile.upload.create'),
                                                type: 'GET',
                                                success: function (data) {
                                                    if(data.status == 'success') {
                                                        BootstrapDialog.show({
                                                            title: TranslateProvider.get('image_cropping_title'),
                                                            type: BootstrapDialog.TYPE_DEFAULT,
                                                            size: BootstrapDialog.SIZE_NORMAL,
                                                            message: function () {
                                                                if(data.status == 'success') {
                                                                    var $content = $(data.html);

                                                                    $content.find('img').attr('src', event.target.result);
                                                                } else {
                                                                    var $content = TranslateProvider.get('Incorrect input data');
                                                                }

                                                                return $content;
                                                            },
                                                            onshown: function (cropp) {
                                                                $img = cropp.$modalBody.find('img');

                                                                $img.cropper({
                                                                    aspectRatio: 3 / 4,
                                                                    strict: false,
                                                                    guides: false,
                                                                    highlight: true,
                                                                    dragCrop: false,
                                                                    cropBoxMovable: true,
                                                                    cropBoxResizable: true,
                                                                    resizable: false,
                                                                    built: function () {
                                                                        $img.cropper("setCropBoxData", {width: 240, height: 180});
                                                                    }
                                                                });
                                                            },
                                                            buttons: [{
                                                                icon: 'fa fa-sign-out',
                                                                label: TranslateProvider.get('image_cropping_cancel'),
                                                                action: function (cropp) {
                                                                    cropp.close();
                                                                }
                                                            }, {
                                                                icon: 'fa fa-floppy-o',
                                                                cssClass: 'btn-success',
                                                                label: TranslateProvider.get('image_cropping_crop'),
                                                                action: function (cropp) {
                                                                    var $item = $('<div class="item"><span id="remove-photo" class="glyphicon glyphicon-remove"></span><div class="canvas"></div></div>');
                                                                    $item.find('.canvas').html($img.cropper('getCroppedCanvas'));
                                                                    $item.find('canvas').css('height', '146px');
                                                                    dialog.$modalBody.find('.image-container .gridly').append($item).gridly();

                                                                    $fileElement.replaceWith($fileElement.clone(true));
                                                                    cropp.close();
                                                                }
                                                            }]
                                                        });
                                                    }
                                                }
                                            });
                                        };

                                        reader.readAsDataURL(event.target.files[0]);
                                    }
                                })
                            }
                        },
                        buttons: [{
                            icon: 'fa fa-sign-out',
                            label: TranslateProvider.get('cancel'),
                            action: function(dialog) {
                                dialog.close();
                            }
                        }, {
                            icon: 'fa fa-floppy-o',
                            cssClass: 'btn-primary' + (data.status != 'success' ? 'disabled' : ''),
                            label: TranslateProvider.get('save'),
                            action: function(dialog) {
                                var $form = $('#edit-templates-form');
                                $form.submit();

                                if($form.find('.has-error').length == 0) {
                                    var formData = new FormData();
                                    formData.append('_method', 'PUT');
                                    formData.append('name', $form.find('#name').val());
                                    formData.append('description', $form.find('#description').val());

                                    $form.find('canvas').each(function (index, value) {
                                        formData.append('photos[' + index + ']', value.toDataURL('image/png'));
                                    });
                                    
                                    var deleted = $form.find('#deleted_photo').val();

                                    if (deleted != '') {
                                        $.each(deleted.split('|'), function (i, v) {
                                            formData.append('deleted[' + i + ']', v);
                                        });
                                    }

                                    var $bar = dialog.$modalBody.find('.upload .progress-bar');
                                    var $text = dialog.$modalBody.find('.upload span');
                                    dialog.$modalBody.find('.upload').removeClass('hidden');

                                    SystemProvider.ajax({
                                        url: $form.attr('action'),
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        success: function(res) {
                                            if (res.status == 'success') {
                                                TableProvider.update($('#templates_table')[0], res.data);
                                                dialog.close();
                                            }
                                        },
                                        xhr: function() {
                                            var xhr = $.ajaxSettings.xhr();

                                            if(xhr.upload) {
                                                xhr.upload.onprogress = function(x) {
                                                    var done = x.loaded;
                                                    var total = x.total;
                                                    var result = Math.round(done * 100 / total);
                                                    console.log(result);

                                                    $bar.css('width', result + '%');
                                                    $text.text(result);
                                                }
                                            }

                                            return xhr;
                                        }
                                    });
                                }
                            }
                        }],
                        message: data.status == 'success' ? data.html : TranslateProvider.get('Incorrect input data')
                    });
                }
            })
        })
        .on('click', '.delete_templates', function (e) {
            e.stopPropagation();
            var $elem = $(e.target).closest('button');
            var $table = $elem.closest('table');
            var $row = $elem.closest('tr');
        
            BootstrapDialog.show({
                title: TranslateProvider.get('templates_delete_confirm_title'),
                type: BootstrapDialog.TYPE_DANGER,
                size: BootstrapDialog.SIZE_NORMAL,
                closable: true,
                draggable: true,
                buttons: [{
                    icon: 'fa fa-sign-out',
                    label: TranslateProvider.get('cancel'),
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    icon: 'fa fa-trash-o',
                    cssClass: 'btn-danger',
                    label: TranslateProvider.get('delete'),
                    action: function(dialog) {
                        SystemProvider.ajax({
                            url: $elem.data('url'),
                            type: 'DELETE',
                            success: function (data) {
                                if (data.status == 'success') {
                                    TableProvider.delete($table[0], $row, data.id);
                                    dialog.close();
                                }
                            }
                        });
                    }
                }],
                message: 'Warning! Drop your banana?'
            });
        })
        .on('click', '#add_products', function (e) {
            e.stopPropagation();

            SystemProvider.ajax({
                url: SystemProvider.getUrl('products.create'),
                type: 'GET',
                success:function(data) {
                    BootstrapDialog.show({
                        title: TranslateProvider.get('products_add_names_modal_title'),
                        type: BootstrapDialog.TYPE_SUCCESS,
                        size: BootstrapDialog.SIZE_NORMAL,
                        closable: false,
                        onshown: function(dialog) {
                            if (data.status == 'success') {
                                var $form = dialog.$modalBody.find('form');
                                Validation.addForm($form)

                                $form.find('#start_date').datetimepicker({
                                    locale: 'ru', //TODO integrel lezun
                                    format: 'DD/MM/YYYY HH:mm',
                                    minDate: new Date(),
                                    sideBySide: true,
                                    showTodayButton: true
                                });

                                $form.find('#offset').val((new Date).getTimezoneOffset());
                            }
                        },
                        buttons: [{
                            icon: 'fa fa-sign-out',
                            label: TranslateProvider.get('cancel'),
                            action: function(dialog) {
                                dialog.close();
                            }
                        }, {
                            icon: 'fa fa-floppy-o',
                            cssClass: 'btn-success' + (data.status != 'success' ? 'disabled' : ''),
                            label: TranslateProvider.get('save'),
                            action: function(dialog) {
                                var $form = $('#add-products-form');
                                $form.submit();

                                if($form.find('.has-error').length == 0) {
                                    SystemProvider.formSubmit($form, function (res) {
                                        if(res.status == 'success') {
                                            TableProvider.add($('#products_table')[0], res.data);
                                            dialog.close();
                                        }
                                    });
                                }
                            }
                        }],
                        message: data.status == 'success' ? data.html : TranslateProvider.get('Incorrect input data')
                    });
                }
            })
        })
        .on('click', '.edit_products', function (e) {
            e.stopPropagation();
            var $elem = $(e.target).closest('button');

            SystemProvider.ajax({
                url: $elem.data('url'),
                type: 'GET',
                success:function(data) {
                    BootstrapDialog.show({
                        title: TranslateProvider.get('products_add_names_modal_title'),
                        type: BootstrapDialog.TYPE_PRIMARY,
                        size: BootstrapDialog.SIZE_NORMAL,
                        closable: false,
                        onshown: function(dialog) {
                            if (data.status == 'success') {
                                var $form = dialog.$modalBody.find('form');
                                Validation.addForm($form);

                                // $form.find('#start_date').val(SystemProvider.getLocalDate($form.find('#start_date').val(), 'date').toISOString());
                                $form.find('#start_date').datetimepicker({
                                    locale: 'ru', //TODO integrel lezun
                                    format: 'DD/MM/YYYY HH:mm',
                                    minDate: SystemProvider.getLocalDate($form.find('#start_date').val()),
                                    sideBySide: true,
                                    showTodayButton: true
                                });

                                $form.find('#offset').val((new Date).getTimezoneOffset());
                            }
                        },
                        buttons: [{
                            icon: 'fa fa-sign-out',
                            label: TranslateProvider.get('cancel'),
                            action: function(dialog) {
                                dialog.close();
                            }
                        }, {
                            icon: 'fa fa-floppy-o',
                            cssClass: 'btn-primary' + (data.status != 'success' ? 'disabled' : ''),
                            label: TranslateProvider.get('save'),
                            action: function(dialog) {
                                var $form = $('#edit-products-form');
                                $form.submit();

                                if($form.find('.has-error').length == 0) {
                                    SystemProvider.formSubmit($form, function (res) {
                                        if(res.status == 'success') {
                                            TableProvider.update($('#products_table')[0], res.data);
                                            dialog.close();
                                        }
                                    });
                                }
                            }
                        }],
                        message: data.status == 'success' ? data.html : TranslateProvider.get('Incorrect input data')
                    });
                }
            })
        })
        .on('click', '.delete_products', function (e) {
            e.stopPropagation();
            var $elem = $(e.target).closest('button');
            var $table = $elem.closest('table');
            var $row = $elem.closest('tr');

            BootstrapDialog.show({
                title: TranslateProvider.get('products_delete_confirm_title'),
                type: BootstrapDialog.TYPE_DANGER,
                size: BootstrapDialog.SIZE_NORMAL,
                closable: true,
                draggable: true,
                buttons: [{
                    icon: 'fa fa-sign-out',
                    label: TranslateProvider.get('cancel'),
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    icon: 'fa fa-trash-o',
                    cssClass: 'btn-danger',
                    label: TranslateProvider.get('delete'),
                    action: function(dialog) {
                        SystemProvider.ajax({
                            url: $elem.data('url'),
                            type: 'DELETE',
                            success: function (data) {
                                if (data.status == 'success') {
                                    TableProvider.delete($table[0], $row, data.id);
                                    dialog.close();
                                }
                            }
                        });
                    }
                }],
                message: 'Warning! Drop your banana?'
            });
        })
        .on('click', '#automatic', function (e) {
            e.stopPropagation();
            var $elem = $(e.target);

            if (SystemProvider.auth) {
                window.node.emit('auctionAutomatic', {
                    type: $elem.prop('checked'),
                    token: SystemProvider.cookies['node'],
                    device: SystemProvider.cookies['device'],
                    product_id: $elem.closest('.auction').data('key'),
                    name: SystemProvider.user['name']
                });

                $('#manual').prop('checked', !$elem.prop('checked'));
            } else {
                $elem.prop('checked', false);

                SystemProvider.showAuth();
            }
        })
        .on('click', '#manual', function (e) {
            e.stopPropagation();
            var $elem = $(e.target);
            var $automatic = $('#automatic');

            $automatic.prop('checked', false);

            if (SystemProvider.auth) {
                window.node.emit('auctionAutomatic', {
                    type: $automatic.prop('checked'),
                    token: SystemProvider.cookies['node'],
                    device: SystemProvider.cookies['device'],
                    product_id: $elem.closest('.auction').data('key'),
                    name: SystemProvider.user['name']
                });
            }
        })
        .on('circle-animation-progress', '#auction-timer', function(event, progress) {
            $(this).find('.text').text(Math.round(progress * 15));
        })
        .on('click', '.make-bid', function (e) {
            e.stopPropagation();
            e.preventDefault();
            var $elem = $(e.target);

            if (SystemProvider.auth) {
                if ($elem.closest('div.auction[data-key]').length > 0) {
                    var $parent = $elem.closest('div.auction[data-key]');

                    window.node.emit('makeBid', {
                        id: $parent.data('key'),
                        token: SystemProvider.cookies['node']
                    });
                } else if( $('.goods-item[data-key]').length > 0) {
                    var $parent = $elem.closest('div.goods-item[data-key]');

                    window.node.emit('makeBid', {
                        id: $parent.data('key'),
                        token: SystemProvider.cookies['node']
                    });
                }
            }
        })
        .on('click', '.auction-apply', function (e) {
            e.stopPropagation();
            e.preventDefault();
            var $elem = $(e.target);
            
            SystemProvider.ajax({
                url: SystemProvider.getUrl('register.auction'),
                data: {
                    product_id: $elem.closest('.goods-item[data-key]').data('key')
                },
                success: function (data) {
                    if (data.status == 'success') {
                        $elem.attr('disabled', 'disabled');
                    }
                }
            })
        })
        .on('show.bs.popover', '.filter-header button', function (e) {

        })
        .on('click', function (e) {
            var $elem = $(e.target);

            if ($elem.attr('aria-describedby') === undefined && $elem.closest('.popover').length == 0) {
                $('.filter-header button[aria-describedby^="popover"]').popover('hide');
            }
        })
        .on('click', '.filter-header button:not(.turquoise)', function (e) {
            e.stopPropagation();
            var $elem = $(e.target);
            $elem.popover('show');

            $('[aria-describedby]').each(function (i, item) {
                var $item = $(item);

                if ($item.data('type') != $elem.data('type')) {
                    $item.popover('hide');
                }
            })
        })
        .on('change', '.filter-criteria', function (e) {
            var $elem = $(e.target);
            var $parent = $elem.closest('.checkbox-content')
            var $filterHeader = $('.filter-header');
            var filters = $filterHeader.data('filter');
            
            filters[$parent.data('type')] = _.chain(filters[$parent.data('type')])
                                             .map(function (item) {
                                                 if (item.id == $elem.val()) {
                                                     item.checked = $elem.prop('checked');
                                                 }

                                                 return item;
                                             })
                                             .value();
            $filterHeader.data('filter', filters);
            
            SystemProvider.ajax({
                url: SystemProvider.getUrl('auction.search'),
                data: {
                    type: $filterHeader.data('type'),
                    filters: filters
                },
                success: function (data) {
                    if (data.status == 'success') {
                        $('.pagination-content').bootpag({
                            total: data.total,
                            maxVisible: data.total < 50 ? Math.ceil(data.total / 10) : 5
                        });

                        $('#container.goods-container').html(data.view);
                    }
                }
            });
        })
        .on('page', '.pagination-content', function (e, page) {
            SystemProvider.ajax({
                url: SystemProvider.getUrl('auction.search'),
                data: {
                    type: $('.filter-header').data('type'),
                    filters: $('.filter-header').data('filter'),
                    page: page
                },
                success: function (data) {
                    if (data.status == 'success') {
                        $('#container.goods-container').html(data.view)
                    }
                }
            })
        });
    
    $('.personal-area').hover(function (e) {
        $(e.target).closest('.personal-area').addClass('open');
    }, function (e) {
        $(e.target).closest('.personal-area').removeClass('open');
    });

    if(window.node !== undefined) {
        var products = [];

        $('.goods-item[data-key]').each(function (index, item) {
            var $item = $(item);

            products.push($item.data('key'));
        });

        window.node.emit('productViewer', {
            products: products
        });
        
        window.node
            .on('auctionStart', function (data) {
                if( $('.goods-item').length > 0) {
                    var $elem = $('.goods-item[data-key="' + data.id + '"]');
                    var $timer = $elem.find('.auction-start-timer');
                    var $timerParent = $timer.parent();
    
                    $timer.remove();
                    $timerParent.append('<div class="auction-timer">15</div>')
                    $elem.find('#user-name label').text('');
                    $elem.find('.bottom button').remove();
                    $elem.find('.bottom').append('<button class="btn btn-circle turquoise curious-turquoise-stripe">' + TranslateProvider.get('goods_count'), +'</button>');
                } else if($('div.auction[data-key="' + data.id + '"]').length > 0) {
                    var $parent = $('div.auction[data-key="' + data.id + '"]');
                    var $process = $parent.find('.auction-process');
    
                    $parent.find('.soon-start').addClass('hidden');
                    $process.removeClass('hidden');
    
                    $process.find('#auction-timer').circleProgress({
                        startAngle: -Math.PI/2,
                        value: 1,
                        size: 90,
                        emptyFill: 'rgba(232, 232, 232, 1)',
                        thickness: 6,
                        // animationStartValue: 1,
                        fill: {color: '#45B6AF'}
                    });
                }
            })
            .on('auctionTimer', function (data) {
                if( $('.goods-item').length > 0) {
                    var $elem = $('[data-key="' + data.id + '"]');

                    // if ($elem.hasClass('register')) {
                    //     var $timer = $elem.find('.auction-start-timer');
                    //     var $timerParent = $timer.parent();
                    //
                    //     $timer.remove();
                    //     $timerParent.append('<div class="auction-timer">15</div>')
                    //     $elem.find('#user-name label').text('');
                    //     $elem.find('.bottom button').remove();
                    //     $elem.find('.bottom').append('<button class="btn btn-circle turquoise curious-turquoise-stripe">' + TranslateProvider.get('goods_count'), +'</button>');
                    // }

                    var $timer = $elem.find('.auction-timer');

                    $timer.text(data.time);
                } else if($('div.auction[data-key="' + data.id + '"]').length > 0) {
                    var $parent = $('div.auction[data-key="' + data.id + '"]');
                    var $process = $parent.find('.auction-process');
                    $process.find('#auction-timer').circleProgress('value', Math.round(data.time / 15 * 100) / 100);
                }
            })
            .on('auctionBid', function (data) {
                if( $('.goods-item').length > 0) {
                    var $elem = $('.goods-item[data-key="' + data.id + '"]');

                    $elem.find('.price span:first').text(data.price + ' руб.');
                    $elem.find('#user-name label').text(data.name);
                } else if($('div.auction[data-key="' + data.id + '"]').length > 0) {
                    var $parent = $('div.auction[data-key="' + data.id + '"]');
                    var $process = $parent.find('.auction-process');
                    var $table = $process.find('.auction-history table tbody');

                    $process.find('#auction-timer').circleProgress('value', 1);
                    $process.find('#current_price').text(Math.round(data.price * 10) / 10);
                    $process.find('#current-winner').text(data.name);

                    var row = '<tr>' +
                                    '<td>' + SystemProvider.getLocalDate(data.date, 'moment').format('HH:mm:ss') + '</td>' +
                                    '<td>' + data.name + '</td>' +
                                    '<td>' + Math.round(data.price * 10) / 10 + ' руб</td>' +
                              '</tr>';
                    $table.prepend(row);
                }
            })
            .on('auctionDetail', function (data) {
                if($('div.auction[data-key="' + data.id + '"]').length > 0) {
                    var $parent = $('div.auction[data-key="' + data.id + '"]');
                    var $process = $parent.find('.auction-process');

                    $process.find('#current_price').text(Math.round(data.price * 10) / 10);
                    $process.find('#automatic').prop('checked', data.automatic);
                    $process.find('#manual').prop('checked', !data.automatic);
                }
            });
    }

    $(window).on('message', function(e){
        var data = e.originalEvent.data;
    });

    $('.auction-start-timer').each(function (index, timer) {
        $timer = $(timer);

        $timer.countdown(SystemProvider.getLocalDate($timer.data('time'), 'date'), function(event) {
            var totalHours = event.offset.totalDays * 24 + event.offset.hours;

            $(this).text(event.strftime(totalHours + ':%M:%S'));
        });
    });

    $('.goods-item .auction-end-timer').each(function (index, timer) {
        $(timer).text(SystemProvider.getLocalDate($timer.data('time'), 'moment').format('DD/MM/YYYY HH:mm:ss'));
    });

    $('.filter-header button').popover({
        html: true,
        placement: 'bottom',
        trigger: 'manual',
        content: function () {
            var $elem = $(this);
            var $parent = $elem.closest('.filter-header');
            
            return _.template(
                '<div data-type="<%= type %>" class="checkbox-content">' +
                    '<% _.each(filters, function(filter) { %>' +
                        '<div class="checkbox">' +
                            '<label>' +
                                '<input type="checkbox" class="filter-criteria" value="<%= filter.id %>" <%= (filter.checked ? "checked" : "") %>> <%= filter.name %>' +
                            '</label>' +
                        '</div>' +
                    '<% }); %>' +
                '</div>'
            )({
                filters: $parent.data('filter')[$elem.data('type')],
                type: $elem.data('type')
            });
        }
    });
});

function formValidation(e){
    e.stopPropagation();
    e.preventDefault();
    var form = $(e.target);
    var model = form.data('model');

    form.find('input:not([name="_token"])').each(function(i, v){
        if(!Validation.valid[form.attr('id')][v.name]) {
            Validation.inputValidation(v, model);
        }
    });
}

function inputValidation(e) {
    var input = $(e.target);

    if(e.target.name) {
        var form = input.closest('form');
        var model = form.data('model');

        if (e.target.name == 'captcha') {
            input.attr('token', '');
        }

        Validation.inputValidation(e.target, model);
    }
}

var Validation = {
    rules: {},
    messages: {},
    valid: {},

    init: function(){
        this.addForm($('form'));
    },

    inputValidation: function(elem, model){
        var name = elem.name;

        name = name.indexOf('_confirmation') > -1 ? name.replace('_confirmation', '') : name;

        if (this.rules[model] == undefined) {
            return;
        }

        if(this.rules[model][name] !== undefined) {
            var rules = this.rules[model][name].split('|');

            for (var i = 0; i < rules.length; i++) {
                var attr = rules[i].split(':');

                switch (attr[0]) {
                    case 'required':
                        if (this.required(elem)) {
                            this.showError(elem, false, this.messages.required.replace(':attribute', name.replace('_id', '')), 'required');
                            return;
                        }
                        break;
                    case 'max':
                        if (this.max(elem, parseInt(attr[1]))) {
                            this.showError(elem, false, this.messages.max.string.replace(':attribute', name).replace(':max', attr[1]), 'max');
                            return;
                        }
                        break;
                    case 'unique':
                        if (this.unique(elem, attr[1])) {
                            this.showError(elem, false, this.messages.unique.replace(':attribute', name), 'unique');
                            return;
                        }
                        break;
                    case 'email':
                        if (this.email(elem)) {
                            this.showError(elem, false, this.messages.email.replace(':attribute', name), 'email');
                            return;
                        }
                        break;
                    case 'min':
                        if (this.min(elem, parseInt(attr[1]))) {
                            this.showError(elem, false, this.messages.min.string.replace(':attribute', name).replace(':min', attr[1]), 'min');
                            return;
                        }
                        break;
                    case 'regex':
                        if (this.regex(elem, attr[1])) {
                            this.showError(elem, false, this.messages.regex.replace(':attribute', name), 'regex');
                            return;
                        }
                        break;
                    case 'confirmed':
                        if (this.confirmed(elem)) {
                            this.showError(elem, false, this.messages.confirmed.replace(':attribute', name), 'confirmed');
                            return;
                        }
                        break;
                    case 'captcha':
                        if (this.captcha(elem)) {
                            this.showError(elem, false, this.messages.captcha.replace(':attribute', name), 'captcha');
                            $('.' + $(elem).data('captcha')).trigger('click');
                            return;
                        }
                        break;
                    case 'size':
                        if (this.size(elem, parseInt(attr[1]))) {
                            this.showError(elem, false, this.messages.size.string.replace(':attribute', name).replace(':size', attr[1]), 'size');
                            return;
                        }
                        break;
                    case 'date_format':
                        if (this.date_format(elem, attr[1])) {
                            this.showError(elem, false, this.messages.date_format.replace(':attribute', name).replace(':format', attr[1]), 'date_format');
                            return;
                        }
                        break;
                    case 'alpha':
                        if (this.alpha(elem)) {
                            this.showError(elem, false, this.messages.alpha.replace(':attribute', name), 'alpha');
                            return;
                        }
                        break;
                    case 'in':
                        if (this.in(elem, attr[1])) {
                            this.showError(elem, false, this.messages.in.replace(':attribute', name), 'in');
                            return;
                        }
                        break;
                    case 'password_check':
                        if (this.password_check(elem)) {
                            this.showError(elem, false, this.messages.in.replace(':attribute', name), 'password_check');
                            return;
                        }
                        break;
                }
            }

            var id = $(elem).closest('form').attr('id');
            this.valid[id][elem.name] = true;
            this.showError(elem, true);
        }
        return;
    },

    required: function(elem) {
        if(elem.nodeName.toLowerCase() == 'select'){
            return $(elem.selectedOptions).val() == '' ? true : false;
        } else if(elem.nodeName.toLowerCase() == 'input') {
            if($(elem).attr('type') == 'checkbox') {
                return !elem.checked;
            }

            return elem.value == '' ? true : false;
        }
    },

    max: function(elem, limit){
        var value = elem.value;
        var type = typeof value;

        switch (type){
            case 'string':
                return value.length > limit ? 'string' : false;
        }
    },

    unique: function(elem, option) {
        var validation = true;

        SystemProvider.ajax({
            url: SystemProvider.getUrl('validateFiled'),
            async: false,
            data: {
                'name'   : elem.name,
                'value'  : elem.value,
                'option' : 'unique:' + option
            },
            success: function(data) {
                if(data.status == 'success') {
                    validation = !data.validation;
                }
            }
        });

        return validation;
    },

    password_check: function (elem) {
        var validation = true;

        SystemProvider.ajax({
            url: SystemProvider.getUrl('validateFiled'),
            async: false,
            data: {
                'name'   : elem.name,
                'value'  : elem.value,
                'option' : 'password_check'
            },
            success: function(data) {
                if(data.status == 'success') {
                    validation = !data.validation;
                }
            }
        });

        return validation;
    },

    email: function(elem){
        var regexp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        return !regexp.test(elem.value);
    },

    min: function(elem, limit){
        var value = elem.value;
        var type = typeof value;

        switch (type){
            case 'string':
                return value.length < limit ? 'string' : false;
        }
    },

    regex: function(elem, pattern){
        pattern = pattern.substr(1, pattern.length - 2);
        var regexp = new RegExp(pattern);

        return !elem.value.match(regexp);
    },

    confirmed: function(elem) {
        var name = elem.name;
        name = name.indexOf('_confirmation') > -1 ? name.replace('_confirmation', '') : name + '_confirmation';
        var confirmation = $(elem).closest('form').find('[name="'+ name +'"]');

        return confirmation.val() != elem.value && confirmation.val() != '';
    },

    captcha: function(elem){
        var validation = true;

        SystemProvider.ajax({
            url: SystemProvider.getUrl('validateFiled'),
            async: false,
            data: {
                '_token': window.token,
                'name': elem.name,
                'value': elem.value,
                'option': 'captcha'
            },
            success: function (data) {
                if (data.status == 'success') {
                    if (data.validation) {
                        $(elem).attr('token', data.token);
                    }

                    validation = !data.validation;
                }
            }
        });

        return validation;
    },

    size: function(elem, limit){
        var value = elem.value;
        var type = typeof value;

        switch (type){
            case 'string':
                return value.length != limit ? 'string' : false;
        }
    },

    date_format: function (elem, format) {
        var pattern = '';

        for(var i = 0; i < format.length; i++) {
            switch (format[i]) {
                case 'd':
                case 'm':
                    pattern += '\\d{2}';
                    break
                case 'Y':
                    pattern += '\\d{4}';
                    break;
                default:
                    pattern += '\\' + format[i];
            }

        }

        var expr = new RegExp(pattern);

        return !expr.test(elem.value);
    },

    alpha: function (elem) {
        var regexp = new RegExp('^[a-zA-Z]+$');

        return !elem.value.match(regexp);
    },

    in: function (elem, array) {
        var array = array.split(',');

        return array.indexOf(elem.value) == -1
    },

    showError: function(elem, ststus, msg, type){
        var group = $(elem).closest('.form-group, .checkbox');
        group.find('#'+ elem.name +'-error').remove();
        if(ststus){
            group.removeClass('has-error');
            group.data('type', '');
        } else if(!group.hasClass('has-error') || group.data('type') != type) {
            group.addClass('has-error');
            if (group.find('div[class*="col-"]').length > 0) {
                group.find('div[class*="col-"]').append('<span id="'+ elem.name +'-error" class="help-block">'+ msg +'</span>');
            } else {
                group.append('<span id="' + elem.name + '-error" class="help-block">' + msg + '</span>');
            }
        }
    },

    addForm: function(forms){
        var that = this;
        if(forms.length > 1) {
            var data = [];

            $.each(forms, function(i, form){
                form = $(form);
                var model = form.data('model');

                if(model !== undefined) {
                    if (that.rules[model] === undefined) {
                        data.push(model)
                    }

                    that.inputValid(form);
                }
            });

            that.getRule(data);
        } else if(forms.length > 0) {
            var model = forms.data('model');

            if(model !== undefined) {
                if (that.rules[model] === undefined) {
                    that.getRule([model]);
                }

                that.inputValid(forms);
            }
        }
    },

    getRule: function(forms){
        var that = this;
        SystemProvider.ajax({
            url: 'validationRules',
            data: {
                forms: forms
            },
            success: function (data) {
                if (data.status == 'success') {
                    for (var rule in data.rules) {
                        that.rules[rule] = data.rules[rule];
                    }

                    that.messages = data.messages;
                }
            }
        });
    },

    inputValid: function($form){
        var id = $form.attr('id');
        var that = this;

        that.valid[id] = {};

        $form.find('input:not([type="hidden"])').each(function(i, v){
            that.valid[id][v.name] = false;
        });
    }
};

var SystemProvider = {
    routes: [],
    offset: 0,
    cookies: [],
    auth: false,
    user: null,

    init: function(){
        var that = this;
        that.offset = (new Date()).getTimezoneOffset();

        this.ajax({
            url: 'routes',
            async: false,
            data: {
                '_token': window.token
            },
            success: function(data){
                if(data.status == 'success') {
                    that.routes = data.routes;
                    that.cookies = data.cookies;
                    that.auth = data.auth;
                    that.user = data.user;
                    that.categories = data.categories;
                }
            }
        });

        $(document).ajaxStart(function(){
            that.loadingStart();
        });

        $(document).ajaxStop(function(){
            that.loadingStop();
        });
    },
    
    loadingStart: function () {
        $('body').append('<div id="ajaxStart" class="modal-backdrop fade in" style="z-index: 1040;">' +
                '<div class="sk-circle" style="margin: 20% auto">'+
                '<div class="sk-circle1 sk-child"></div>'+
                '<div class="sk-circle2 sk-child"></div>'+
                '<div class="sk-circle3 sk-child"></div>'+
                '<div class="sk-circle4 sk-child"></div>'+
                '<div class="sk-circle5 sk-child"></div>'+
                '<div class="sk-circle6 sk-child"></div>'+
                '<div class="sk-circle7 sk-child"></div>'+
                '<div class="sk-circle8 sk-child"></div>'+
                '<div class="sk-circle9 sk-child"></div>'+
                '<div class="sk-circle10 sk-child"></div>'+
                '<div class="sk-circle11 sk-child"></div>'+
                '<div class="sk-circle12 sk-child"></div>'+
                '</div>' +
            '</div>');
    },
    
    loadingStop: function () {
        $('#ajaxStart').remove();
    },

    formSubmit: function(form, callback) {
        var action = form.attr('action');
        var data = this.serialize(form);

        form.find('select').each(function(i, v) {
            var $select = $(v);

            if ($select.attr('multiple') !== undefined) {
                $select.find('option:selected').each(function (j, option) {
                    data[v.name + '[' + j + ']'] = option.value;
                });
            } else {
                data[v.name] = $select.find('option:selected').val();
            }
        });

        form.find('input:not([name="_token"])').each(function(i, v){
            if(!Validation.valid[form.attr('id')][v.name]) {
                Validation.inputValidation(v, form.data('model'));
            }
        });

        if(form.find('.has-error').length == 0) {
            this.ajax({
                url: action,
                data: data,
                // type: form.find('[name="_method"]').val() ? form.find('[name="_method"]').val() : 'POST',
                success: function (data) {
                    callback(data);
                }
            });
        }
    },

    ajax: function(option) {
        var that = this;
        var data = null;

        if(option.data !== undefined && option.data.constructor.name == 'FormData') {
            option.data.append('_token', window.token);
        } else {
            option.data = $.extend(option.data, {
                '_token': window.token
            });
        }

        $.ajax({
            url: window.baseUrl + option.url.replace(window.baseUrl, ''),
            data: option.data,
            type: option.type !== undefined ? option.type : 'POST',
            async: option.async !== undefined ? option.async : true,
            contentType: option.contentType !== undefined ? option.contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
            processData: option.processData !== undefined ? option.processData : true,
            success: function(res){
                if(that.is_json(res)){
                    if(res.status == 'not_access') {
                        return false;
                    }
                }

                if(option.success) {
                    option.success(res);
                }

                data = res;
            },
            xhr: option.xhr !== undefined ? option.xhr : function() {
                var xhr = $.ajaxSettings.xhr();

                return xhr;
            }
        });

        if(option.async !== undefined && !option.async) {
            return data;
        }
    },

    serialize: function(form) {
        var data = {};

        form.find('input').each(function(i, v){
            if($(v).attr('type') == 'checkbox') {
                data[v.name] = v.checked ? 1 : 0;
            } else if($(v).attr('type') == 'radio') {
                if($(v).is(':checked'))
                    data[v.name] = v.value;
            }
            else {
                data[v.name] = v.value;
            }
        });

        return data;
    },

    redirect: function(url){
        window.location.href = url;
    },

    is_json: function(text){
        try {
            JSON.parse(text);
        } catch (e){
            return false;
        }

        return true;
    },

    getUrl: function(route) {
        return _.chain(this.routes)
                .find(function(v){
                    if(v.as != null){
                        return v.as == route;
                    } else {
                        return v.url.indexOf(route) > -1
                    }
                })
                .value().url;
    },

    getLocalDate: function (timestamp, type) {
        type = type || 'moment'
        timestamp = (parseInt(timestamp)/* - (this.offset * 120)*/) * 1000;
        var date = new Date();
        date.setTime(timestamp);

        switch (type) {
            case 'moment':
                return moment(date);
                break;
            case 'date':
                return date;
                break;
        }
    },
    
    showAuth: function () {
        SystemProvider.ajax({
            url: SystemProvider.getUrl('setUrl'),
            data: {
                url: location.href
            },
            success: function (data) {
                if (data.status == 'success') {
                    $('#login').trigger('click');
                }
            }
        })
    }
};

var TableProvider = {
    data: {},
    tables: {},
    columns: {},

    init: function(){
        var  that = this;

        $('body').find('table').each(function(i, v){
            switch (v.id){
                case 'users_table':
                    that.users(v);
                    break;
                case 'names_table':
                    that.names(v);
                    break;
                case 'categories_table':
                    that.categories(v);
                    break;
                case 'templates_table':
                    that.templates(v);
                    break;
                case 'products_table':
                    that.products(v);
                    break;
            }
        });
    },

    users: function(table){
        var that  = this;
        var $table = $(table);
        var url = $table.data('url');
        var tableData = [];

        that.columns[table.id] = function(model) {
            return {
                DT_RowId: model.id,
                id: model.id,
                name: model.name,
                email: model.email,
                created: moment(new Date(model.created_at)).format('DD/MM/YYYY HH:mm:ss'),
                modified: moment(new Date(model.updated_at)).format('DD/MM/YYYY HH:mm:ss'),
                action: '<div class="role_action">' +
                            (model.admin
                                ? '<button class="btn btn-danger btn-xs unset-admin" data-id="' + model.id + '"><i class="fa fa-minus-square"></i> ' + TranslateProvider.get('unset_admin_button_title') + '</button>'
                                : '<button class="btn btn-success btn-xs set-admin" data-id="' + model.id + '"><i class="fa fa-plus-square"></i> ' + TranslateProvider.get('set_admin_button_title') + '</button>'
                            ) +
                        '</div>'
            };
        };

        that.data[table.id] = SystemProvider.ajax({url: url, async: false});

        $.each(that.data[table.id], function(i, v){
            tableData.push(that.columns[table.id](v));
        });

        that.tables[table.id] = $('#users_table').dataTable({
            data: tableData,
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'email' },
                { data: 'created' },
                { data: 'modified' },
                { data: 'action' }
            ],
            columnDefs: [{
                targets: [0],
                orderable: false
            },{
                targets: [5],
                orderable: false
            }],
            aaSorting: []
        });
    },

    names: function(table){
        var that  = this;
        var $table = $(table);
        var url = $table.data('url');
        var tableData = [];

        that.columns[table.id] = function(model) {
            return {
                DT_RowId: model.id,
                id: model.id,
                name: model.name,
                created: moment(new Date(model.created_at)).format('DD/MM/YYYY HH:mm:ss'),
                modified: moment(new Date(model.updated_at)).format('DD/MM/YYYY HH:mm:ss'),
                action: '<div class="btn-group">'+
                            '<button class="btn btn-primary btn-sm edit_names" data-url="'+ SystemProvider.getUrl('names.edit').replace('{names}', model.id) +'">' +
                                '<i class="fa fa-edit"></i> ' +
                                TranslateProvider.get('names_edit_names_button') +
                            '</button>' +
                            '<button class="btn btn-danger btn-sm delete_names" data-url="'+ SystemProvider.getUrl('names.destroy').replace('{names}', model.id) +'">' +
                                '<i class="fa fa-trash-o"></i> ' +
                                TranslateProvider.get('names_delete_names_button') +
                            '</button>'+
                        '</div>'
            };
        };

        that.data[table.id] = SystemProvider.ajax({url: url, async: false});

        $.each(that.data[table.id], function(i, v){
            tableData.push(that.columns[table.id](v));
        });

        that.tables[table.id] = $('#names_table').dataTable({
            data: tableData,
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'created' },
                { data: 'modified' },
                { data: 'action' }
            ],
            columnDefs: [{
                targets: [0],
                orderable: false
            },{
                targets: [4],
                orderable: false
            }],
            aaSorting: []
        });
    },

    categories: function(table){
        var that  = this;
        var $table = $(table);
        var url = $table.data('url');
        var tableData = [];

        that.columns[table.id] = function(model) {
            return {
                DT_RowId: model.id,
                id: model.id,
                name: model.name,
                created: moment(new Date(model.created_at)).format('DD/MM/YYYY HH:mm:ss'),
                modified: moment(new Date(model.updated_at)).format('DD/MM/YYYY HH:mm:ss'),
                action: '<div class="btn-group">'+
                            '<button class="btn btn-primary btn-sm edit_categories" data-url="'+ SystemProvider.getUrl('categories.edit').replace('{categories}', model.id) +'">' +
                                '<i class="fa fa-edit"></i> ' +
                                TranslateProvider.get('categories_edit_names_button') +
                            '</button>' +
                            '<button class="btn btn-danger btn-sm delete_categories" data-url="'+ SystemProvider.getUrl('categories.destroy').replace('{categories}', model.id) +'">' +
                                '<i class="fa fa-trash-o"></i> ' +
                                TranslateProvider.get('categories_delete_names_button') +
                            '</button>'+
                        '</div>'
            };
        };

        that.data[table.id] = SystemProvider.ajax({url: url, async: false});

        $.each(that.data[table.id], function(i, v){
            tableData.push(that.columns[table.id](v));
        });

        that.tables[table.id] = $('#categories_table').dataTable({
            data: tableData,
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'created' },
                { data: 'modified' },
                { data: 'action' }
            ],
            columnDefs: [{
                targets: [0],
                orderable: false
            },{
                targets: [4],
                orderable: false
            }],
            aaSorting: []
        });
    },

    templates: function(table){
        var that  = this;
        var $table = $(table);
        var url = $table.data('url');
        var tableData = [];

        that.columns[table.id] = function(model) {
            return {
                DT_RowId: model.id,
                id: '<a class="show_templates">' + model.id + '</a>',
                name: model.name,
                description: model.description.substr(0, 100),
                created: moment(new Date(model.created_at)).format('DD/MM/YYYY HH:mm:ss'),
                modified: moment(new Date(model.updated_at)).format('DD/MM/YYYY HH:mm:ss'),
                action: '<div class="btn-group">'+
                            '<button class="btn btn-primary btn-sm edit_templates" data-url="'+ SystemProvider.getUrl('templates.edit').replace('{templates}', model.id) +'">' +
                                '<i class="fa fa-edit"></i> ' +
                                TranslateProvider.get('templates_edit_names_button') +
                            '</button>' +
                            '<button class="btn btn-danger btn-sm delete_templates" data-url="'+ SystemProvider.getUrl('templates.destroy').replace('{templates}', model.id) +'">' +
                                '<i class="fa fa-trash-o"></i> ' +
                                TranslateProvider.get('templates_delete_names_button') +
                            '</button>'+
                        '</div>'
            };
        };

        that.data[table.id] = SystemProvider.ajax({url: url, async: false});

        $.each(that.data[table.id], function(i, v){
            tableData.push(that.columns[table.id](v));
        });

        that.tables[table.id] = $('#templates_table').dataTable({
            data: tableData,
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'description' },
                { data: 'created' },
                { data: 'modified' },
                { data: 'action' }
            ],
            columnDefs: [{
                targets: [0],
                orderable: false
            },{
                targets: [5],
                orderable: false
            }],
            aaSorting: []
        });
    },

    products: function(table){
        var that  = this;
        var $table = $(table);
        var url = $table.data('url');
        var tableData = [];

        that.columns[table.id] = function(model) {
            return {
                DT_RowId: model.id,
                id: model.id,
                start_date: SystemProvider.getLocalDate(model.start_date).format('DD/MM/YYYY HH:mm:ss'),
                type: TranslateProvider.get('products_type_' + model.type),
                category: model.category.name,
                source: TranslateProvider.get('products_source_' + model.source),
                created: moment(new Date(model.created_at)).format('DD/MM/YYYY HH:mm:ss'),
                modified: moment(new Date(model.updated_at)).format('DD/MM/YYYY HH:mm:ss'),
                action: '<div class="btn-group">'+
                            '<button class="btn btn-primary btn-sm edit_products" data-url="'+ SystemProvider.getUrl('products.edit').replace('{products}', model.id) +'">' +
                                '<i class="fa fa-edit"></i> ' +
                                TranslateProvider.get('products_edit_names_button') +
                            '</button>' +
                            '<button class="btn btn-danger btn-sm delete_products" data-url="'+ SystemProvider.getUrl('products.destroy').replace('{products}', model.id) +'">' +
                                '<i class="fa fa-trash-o"></i> ' +
                                TranslateProvider.get('products_delete_names_button') +
                            '</button>'+
                        '</div>'
            };
        };

        that.data[table.id] = SystemProvider.ajax({url: url, async: false});

        $.each(that.data[table.id], function(i, v){
            tableData.push(that.columns[table.id](v));
        });

        that.tables[table.id] = $('#products_table').dataTable({
            data: tableData,
            columns: [
                { data: 'id' },
                { data: 'start_date' },
                { data: 'type' },
                { data: 'category' },
                { data: 'source' },
                { data: 'created' },
                { data: 'modified' },
                { data: 'action' }
            ],
            columnDefs: [{
                targets: [0],
                orderable: false
            },{
                targets: [7],
                orderable: false
            }],
            aaSorting: []
        });
    },

    update: function(table, data){
        var that  = this;
        var _table = that.tables[table.id];
        var formater = that.columns[table.id];

        that.data[table.id] = _.chain(that.data[table.id])
                                .map(function(v){
                                    return v.id == data.id ? data : v;
                                })
                                .value();

        _table.fnClearTable();
        _table.fnAddData(_.chain(that.data[table.id])
                    .map(function(v){
                        return formater(v);
                    })
                    .value());
    },

    delete: function(table, $tr, option) {
        var that = this;
        var _table = that.tables[table.id];

        that.data[table.id] = _.chain(that.data[table.id])
                                .filter(function(v){
                                    if(typeof(option) == 'object'){
                                        var result = true;

                                        for(var key in option) {
                                            result = v[key] == option[key];

                                            if(!result) {
                                                break;
                                            }
                                        }

                                        return !result;
                                    } else {
                                        return v.id != option
                                    }
                                })
                                .value();

        _table.DataTable().row($tr).remove().draw();
    },

    getItemIndexByValue: function($table, value){
        var that = this;
        var index = -1;

        $table.find('thead tr th').each(function(i, v){
            if(that.trim($(v).text()) == value){
                index = i;

                return false;
            }
        });

        return index;
    },

    getValueByColumnName: function($tr, name){
        var index = this.getItemIndexByValue($tr.closest('table') ,name);

        return index > 0 ? $tr.find('td:eq('+ index +')').text() : '';
    },

    trim: function(value) {
        return value.replace(/[\r\n]*/g, "").trim();
    },

    add: function(table, data) {
        var that  = this;
        var _table = that.tables[table.id];
        var formater = that.columns[table.id];

        that.data[table.id].push(data);

        _table.fnClearTable();
        _table.fnAddData(_.chain(that.data[table.id])
                         .map(function(v){
                             return formater(v);
                         })
                         .value());
    },

    find: function(table, id) {
        var that = this;
        var _table = that.tables[table.id];

        return _.chain(that.data[table.id])
                .find(function(v){
                    return v.id == id
                })
                .value();
    }
};

var TranslateProvider = {
    data: {},

    init: function(){
        var data = SystemProvider.ajax({
            url: 'translation',
            async: false
        });

        if(data.status == 'success'){
            this.data = data.translation;
        }
    },

    get: function(key){
        return this.data[key];
    }
};