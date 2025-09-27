$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('click', '#confirmation', function (e) {
        e.preventDefault();
        let getHref = $(this).attr('href');
        let dataTableId = $(this).data('datatable_id');
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: getHref,
                    method: 'DELETE',
                    success: function (response) {
                        if (response['status'] == 'success') {
                            toastr.success(response.message);
                            $(dataTableId).DataTable().ajax.reload(null, false);
                        }
                    },
                    error: function () {
                        toastr.error("Some issue occured");
                    }
                });
            }
        });
    });
    $(document).on('click', '.change_status', function () {
        let getId = $(this).data('id');
        let getUrl = $(this).data('url');
        let isChecked = $(this).is(':checked');
        $.ajax({
            url: getUrl,
            type: 'get',
            data: {
                id: getId,
                status: isChecked
            },
            success: function (response) {
                if (response['status'] == "success") {
                    toastr.success("status changed successfully");
                }
            },
            error: function () {
                toastr.error("warning");
            }
        });
    });
    // store general setting
    $(document).on('click', '#store-general-setting', function (e) {
        e.preventDefault();
        let form = $('#create-general-setting-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-general-setting');
        btn.prop('disabled', true);
        $.ajax({
            url: getUrl,
            type: 'post',
            processData: false,
            contentType: false,
            data: data,
            success: function (response) {
                if (response.status == "success") {

                    btn.prop('disabled', false);
                    form[0].reset();
                    modal.modal('hide');
                    toastr.success(response.message);
                    $("#generalsettings-table").DataTable().ajax.reload(null, false);
                }

            }, error: function (xhr) {
                btn.prop('disabled', false);

                if (xhr.status === 422) {
                    modal.modal('show');
                    $('.text-danger').remove(); // Clear all old error messages

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        let fieldName;

                        // Convert dot notation to array format: address.en => address[en]
                        if (key.includes('.')) {
                            const parts = key.split('.');
                            fieldName = parts.shift() + '[' + parts.join('][') + ']';
                        } else {
                            fieldName = key;
                        }

                        // Select by name attribute

                        const inputField = $(`#create-general-setting-form [name="${fieldName}"]`);

                        if (inputField.length > 0) {
                            inputField.next('.text-danger').remove();
                            inputField.after(`<p class="text-danger">${value[0]}</p>`);
                        } else {
                            console.warn('Field not found:', fieldName);
                        }
                    });
                }
            }
        });
    });

    // edit general setting
    $(document).on('click', '.edit-general-setting-btn', function (e) {
        e.preventDefault();
        let getUrl = $(this).attr("href");
        $.ajax({
            url: getUrl,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {
                    $("#edit-general-setting-form [name='site_name']").val(response.data.site_name);
                    $("#edit-general-setting-form [name='facebook']").val(response.data.facebook);
                    $("#edit-general-setting-form [name='instagram']").val(response.data.instagram);
                    $("#edit-general-setting-form [name='telegram']").val(response.data.telegram);
                    $("#edit-general-setting-form [name='whatsapp']").val(response.data.whatsapp);
                    $("#edit-general-setting-form [name='youtube']").val(response.data.youtube);
                    $("#edit-general-setting-form [name='x']").val(response.data.x);
                    $("#edit-general-setting-form [name='linkedin']").val(response.data.linkedin);
                    $("#show-logo").attr('src', response.data.logo);
                    let actionUrl = `general-settings/${response.data.id}`;
                    $("#edit-general-setting-form").attr('action', actionUrl);
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error("An unexpected error occurred.");
                }
            }
        });
    });
    // update general setting
    $(document).on('click', '#update-general-setting', function (e) {
        e.preventDefault();
        let form = $('#edit-general-setting-form');
        let getUrl = form.attr('action');
        let data = new FormData(form[0]);
        data.append('_method', 'PUT');
        let modal = $('#edit-general-setting');
        $.ajax({
            url: getUrl,
            type: 'post',
            data: data,
            processData: false,
            contentType: false,

            success: function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    $('#generalsettings-table').DataTable().ajax.reload(null, false);
                    modal.modal('hide');
                }
            },
            error: function (xhr) {


                if (xhr.status === 422) {
                    modal.modal('show');
                    $('.text-danger').remove(); // Clear all old error messages

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        let fieldName;

                        // Convert dot notation to array format: address.en => address[en]
                        if (key.includes('.')) {
                            const parts = key.split('.');
                            fieldName = parts.shift() + '[' + parts.join('][') + ']';
                        } else {
                            fieldName = key;
                        }

                        // Select by name attribute
                        const inputField = $(`#edit-general-setting-form [name="${fieldName}"]`);

                        if (inputField.length > 0) {
                            inputField.next('.text-danger').remove();
                            inputField.after(`<p class="text-danger">${value[0]}</p>`);
                        } else {
                            console.warn('Field not found:', fieldName);
                        }
                    });
                }
            }
        })
    });
    // store contact
    $(document).on('click', '#store-contact', function (e) {
        e.preventDefault();
        let form = $('#create-contact-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-contact');
        btn.prop('disabled', true);
        $.ajax({
            url: getUrl,
            type: 'post',
            processData: false,
            contentType: false,
            data: data,
            success: function (response) {
                if (response.status == "success") {

                    btn.prop('disabled', false);
                    form[0].reset();
                    modal.modal('hide');
                    toastr.success(response.message);
                    $("#contacts-table").DataTable().ajax.reload(null, false);
                }

            }, error: function (xhr) {
                btn.prop('disabled', false);

                if (xhr.status === 422) {
                    modal.modal('show');
                    $('.text-danger').remove(); // Clear all old error messages

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        let fieldName;

                        // Convert dot notation to array format: address.en => address[en]
                        if (key.includes('.')) {
                            const parts = key.split('.');
                            fieldName = parts.shift() + '[' + parts.join('][') + ']';
                        } else {
                            fieldName = key;
                        }

                        // Select by name attribute

                        const inputField = $(`#create-contact-form [name="${fieldName}"]`);

                        if (inputField.length > 0) {
                            inputField.next('.text-danger').remove();
                            inputField.after(`<p class="text-danger">${value[0]}</p>`);
                        } else {
                            console.warn('Field not found:', fieldName);
                        }
                    });
                }
            }
        });
    });
    // edit contact btn
    $(document).on('click', '.edit-contact-btn', function (e) {
        e.preventDefault();
        let getUrl = $(this).attr("href");
        $.ajax({
            url: getUrl,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {
                    window.availableLanguages.forEach(lang => {
                        $("#edit-contact-form [name='state[" + lang + "]']").val(response.data.state?.[lang] ?? '');
                        $("#edit-contact-form [name='address[" + lang + "]']").val(response.data.address?.[lang] ?? '');
                    });

                    let select = $("#edit-contact-form [name='general_setting_id']");
                    select.empty();
                    response.generalSettings.forEach(generalSetting => {
                        let selected = response.data.general_setting_id == generalSetting.id ? 'selected' : '';
                        select.append(`<option value="${generalSetting.id}" ${selected}>${generalSetting.site_name}</option>`)
                    });
                    $("#edit-contact-form [name='site_name']").val(response.data.site_name);
                    $("#edit-contact-form [name='site_name']").val(response.data.site_name);
                    $("#edit-contact-form [name='email']").val(response.data.email);
                    $("#edit-contact-form [name='is_primary']").val(response.data.is_primary).trigger('change')
                    let actionUrl = `contacts/${response.data.id}`;
                    $("#edit-contact-form").attr('action', actionUrl);
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error("An unexpected error occurred.");
                }
            }
        });
    });
    // update contact
    $(document).on('click', '#update-contact', function (e) {
        e.preventDefault();
        let form = $('#edit-contact-form');
        let getUrl = form.attr('action');
        let data = new FormData(form[0]);
        data.append('_method', 'PUT');
        let modal = $('#edit-contact');
        $.ajax({
            url: getUrl,
            type: 'post',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    $('#contacts-table').DataTable().ajax.reload(null, false);
                    modal.modal('hide');
                }
            },
            error: function (xhr) {


                if (xhr.status === 422) {
                    modal.modal('show');
                    $('.text-danger').remove(); // Clear all old error messages

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        let fieldName;

                        // Convert dot notation to array format: address.en => address[en]
                        if (key.includes('.')) {
                            const parts = key.split('.');
                            fieldName = parts.shift() + '[' + parts.join('][') + ']';
                        } else {
                            fieldName = key;
                        }
                        // Select by name attribute
                        const inputField = $(`#edit-contact-form [name="${fieldName}"]`);

                        if (inputField.length > 0) {
                            inputField.next('.text-danger').remove();
                            inputField.after(`<p class="text-danger">${value[0]}</p>`);
                        } else {
                            console.warn('Field not found:', fieldName);
                        }
                    });
                }
            }
        })
    });
    // store phone
    $(document).on('click', '#store-phone', function (e) {
        e.preventDefault();
        let form = $('#create-phone-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-phone');
        btn.prop('disabled', true);
        $.ajax({
            url: getUrl,
            type: 'post',
            processData: false,
            contentType: false,
            data: data,
            success: function (response) {
                if (response.status == "success") {

                    btn.prop('disabled', false);
                    form[0].reset();
                    modal.modal('hide');
                    toastr.success(response.message);
                    $("#phones-table").DataTable().ajax.reload(null, false);
                }
            }, error: function (xhr) {
                btn.prop('disabled', false);

                if (xhr.status === 422) {
                    modal.modal('show');
                    $('.text-danger').remove(); // Clear all old error messages

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        let fieldName;

                        // Convert dot notation to array format: address.en => address[en]
                        if (key.includes('.')) {
                            const parts = key.split('.');
                            fieldName = parts.shift() + '[' + parts.join('][') + ']';
                        } else {
                            fieldName = key;
                        }

                        // Select by name attribute

                        const inputField = $(`#create-phone-form [name="${fieldName}"]`);

                        if (inputField.length > 0) {
                            inputField.next('.text-danger').remove();
                            inputField.after(`<p class="text-danger">${value[0]}</p>`);
                        } else {
                            console.warn('Field not found:', fieldName);
                        }
                    });
                }
            }
        });
    });
    // edit phone
    $(document).on('click', '.edit-phone-btn', function (e) {
        e.preventDefault();
        let getUrl = $(this).attr("href");
        $.ajax({
            url: getUrl,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {

                    let select = $("#edit-phone-form [name='contact_id']");
                    select.empty();
                    response.contacts.forEach(contact => {
                        selected = contact.id == response.data.contact_id ? 'selected' : '';
                        select.append(`<option name='contact_id' value='${contact.id}' ${selected}>${contact.state['en']}</option>`)
                    });
                    $("#edit-phone-form [name='phone_number']").val(response.data.phone_number);
                    let actionUrl = `phones/${response.data.id}`;
                    $("#edit-phone-form").attr('action', actionUrl);
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error("An unexpected error occurred.");
                }
            }
        });
    });
    // update phone
    $(document).on('click', '#update-phone', function (e) {
        e.preventDefault();
        let form = $('#edit-phone-form');
        let getUrl = form.attr('action');
        let data = new FormData(form[0]);
        data.append('_method', 'PUT');
        let modal = $('#edit-phone');
        $.ajax({
            url: getUrl,
            type: 'post',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    $('#phones-table').DataTable().ajax.reload(null, false);
                    modal.modal('hide');
                }
            },
            error: function (xhr) {


                if (xhr.status === 422) {
                    modal.modal('show');
                    $('.text-danger').remove(); // Clear all old error messages

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        let fieldName;

                        // Convert dot notation to array format: address.en => address[en]
                        if (key.includes('.')) {
                            const parts = key.split('.');
                            fieldName = parts.shift() + '[' + parts.join('][') + ']';
                        } else {
                            fieldName = key;
                        }
                        // Select by name attribute
                        const inputField = $(`#edit-phone-form [name="${fieldName}"]`);

                        if (inputField.length > 0) {
                            inputField.next('.text-danger').remove();
                            inputField.after(`<p class="text-danger">${value[0]}</p>`);
                        } else {
                            console.warn('Field not found:', fieldName);
                        }
                    });
                }
            }
        })
    });
    // Store slider
    $(document).on('click', '#store-slider', function (e) {
        e.preventDefault();
        let form = $('#create-slider-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-slider');
        btn.prop('disabled', true);
        $.ajax({
            url: getUrl,
            type: 'post',
            processData: false,
            contentType: false,
            data: data,
            success: function (response) {
                if (response.status == "success") {

                    btn.prop('disabled', false);
                    form[0].reset();
                    modal.modal('hide');
                    toastr.success(response.message);
                    $("#sliders-table").DataTable().ajax.reload(null, false);
                }

            }, error: function (xhr) {
                btn.prop('disabled', false);

                if (xhr.status === 422) {
                    modal.modal('show');
                    $('.text-danger').remove(); // Clear all old error messages

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        let fieldName;

                        // Convert dot notation to array format: address.en => address[en]
                        if (key.includes('.')) {
                            const parts = key.split('.');
                            fieldName = parts.shift() + '[' + parts.join('][') + ']';
                        } else {
                            fieldName = key;
                        }

                        // Select by name attribute

                        const inputField = $(`#create-slider-form [name="${fieldName}"]`);

                        if (inputField.length > 0) {
                            inputField.next('.text-danger').remove();
                            inputField.after(`<p class="text-danger">${value[0]}</p>`);
                        } else {
                            console.warn('Field not found:', fieldName);
                        }
                    });
                }
            }
        });
    });
    // edit slider
    $(document).on('click', '.edit-slider-btn', function (e) {
        e.preventDefault();
        let getUrl = $(this).attr("href");
        $.ajax({
            url: getUrl,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {
                    console.log(response.data);

                    window.availableLanguages.forEach(lang => {
                        $("#edit-slider-form [name='title[" + lang + "]']").val(response.data.title?.[lang] ?? '');
                        $("#edit-slider-form [name='description[" + lang + "]']").val(response.data.description?.[lang] ?? '');
                    });

                    $("#edit-slider-form [name='status']").val(response.data.status).trigger('change');
                    let actionUrl = `sliders/${response.data.id}`;
                    $("#edit-slider-form").attr('action', actionUrl);
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error("An unexpected error occurred.");
                }
            }
        });
    });
    // update general settings
    $(document).on('click', '#update-slider', function (e) {
        e.preventDefault();
        let form = $('#edit-slider-form');
        let getUrl = form.attr('action');
        let data = new FormData(form[0]);
        data.append('_method', 'PUT');
        let modal = $('#edit-slider');
        $.ajax({
            url: getUrl,
            type: 'post',
            data: data,
            processData: false,
            contentType: false,

            success: function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    $('#sliders-table').DataTable().ajax.reload(null, false);
                    modal.modal('hide');
                }
            },
            error: function (xhr) {


                if (xhr.status === 422) {
                    modal.modal('show');
                    $('.text-danger').remove();

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        let fieldName;

                        // Convert dot notation to array format: address.en => address[en]
                        if (key.includes('.')) {
                            const parts = key.split('.');
                            fieldName = parts.shift() + '[' + parts.join('][') + ']';
                        } else {
                            fieldName = key;
                        }

                        // Select by name attribute
                        const inputField = $(`#edit-slider-form [name="${fieldName}"]`);

                        if (inputField.length > 0) {
                            inputField.next('.text-danger').remove();
                            inputField.after(`<p class="text-danger">${value[0]}</p>`);
                        } else {
                            console.warn('Field not found:', fieldName);
                        }
                    });
                }
            }
        })
    });
    // store slider image
    $(document).on('click', '#store-slider-image', function (e) {
        e.preventDefault();
        let form = $('#create-slider-image-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-slider-image');
        btn.prop('disabled', true);
        $.ajax({
            url: getUrl,
            type: 'post',
            processData: false,
            contentType: false,
            data: data,
            success: function (response) {
                if (response.status == "success") {

                    btn.prop('disabled', false);
                    form[0].reset();
                    modal.modal('hide');
                    toastr.success(response.message);
                    window.location.reload();
                }

            }, error: function (xhr) {
                btn.prop('disabled', false);

                if (xhr.status === 422) {
                    modal.modal('show');
                    $('.text-danger').remove(); // Clear all old error messages

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        let fieldName;

                        if (key.includes('.')) {
                            const parts = key.split('.');
                            fieldName = parts.shift() + '[' + parts.join('][') + ']';
                        } else {
                            fieldName = key;
                        }

                        // ✅ Fix for image[]
                        if (fieldName === 'image') {
                            fieldName = 'image[]';
                        }

                        const inputField = $(`[name="${fieldName}"]`);

                        if (inputField.length > 0) {
                            inputField.next('.text-danger').remove();
                            inputField.after(`<p class="text-danger">${value[0]}</p>`);
                        } else {
                            console.warn('Field not found:', fieldName);
                        }
                    });
                }
            }
        });
    });



})
