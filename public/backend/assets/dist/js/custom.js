$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // $('.select2').select2();
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
    // update slider
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
    // Load category parent select
    $('#create-category').on('shown.bs.modal', function () {
        let modal = $(this);
        let url = modal.data('load-parent-url');

        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                if (response.status == "success") {
                    modal.find('#parent-select-wrapper').html(response.html);
                    modal.find('#parent-select-wrapper .select2').select2({
                        placeholder: "Select Parent Category",
                        allowClear: true,
                        dropdownParent: modal
                    });
                }
            }
        });
    });
    // load product_id and attribute to product_variants
    $('#create-product-variant').on('shown.bs.modal', function () {
        let modal = $(this);
        modal.find('.select2').each(function () {
            $(this).select2({
                placeholder: "Select attribute",
                allowClear: true,
                dropdownParent: modal
            });
        });
    });
    $('#edit-product-variant').on('shown.bs.modal', function () {
        let modal = $(this);
        modal.find('.select2').each(function () {
            $(this).select2({
                placeholder: "Select attribute",
                allowClear: true,
                dropdownParent: modal
            });
        });
    });
    // load product in stock
    $('#create-stock').on('shown.bs.modal', function () {
        let modal = $(this);
        modal.find('.select2').each(function () {
            $(this).select2({
                placeholder: "Select product",
                allowClear: true,
                dropdownParent: modal
            });
        });
    });
    $('#edit-stock').on('shown.bs.modal', function () {
        let modal = $(this);
        modal.find('.select2').each(function () {
            $(this).select2({
                placeholder: "Select product",
                allowClear: true,
                dropdownParent: modal
            });
        });
    });

    // Load category parent edit select
    // $('#edit-category').on('shown.bs.modal', function () {
    //     let modal = $(this);
    //     let url = modal.data('load-parent-url');
    //     console.log(url);


    //     $.ajax({
    //         url: url,
    //         type: 'GET',
    //         success: function (response) {
    //             if (response.status == "success") {
    //                 modal.find('#parent-edit-select-wrapper').html(response.html);
    //                 modal.find('#parent-edit-select-wrapper .select2').select2({
    //                     placeholder: "Select Parent Category",
    //                     allowClear: true,
    //                     dropdownParent: modal
    //                 });
    //             }
    //         }
    //     });
    // });

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
    // store cateogry
    $(document).on('click', '#store-category', function (e) {
        e.preventDefault();
        let form = $('#create-category-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-category');
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
                    $("#categories-table").DataTable().ajax.reload(null, false);
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

                        const inputField = $(`#create-category-form [name="${fieldName}"]`);

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
    // edit category
    $(document).on('click', '.edit-category-btn', function (e) {
        e.preventDefault();
        let getUrl = $(this).attr("href");
        let modal = $('#edit-category');
        $.ajax({
            url: getUrl,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {
                    modal.find('#parent-edit-select-wrapper').html(response.html);
                    modal.find('#parent-edit-select-wrapper .select2').select2({
                        placeholder: "Select Parent Category",
                        allowClear: true,
                        dropdownParent: modal
                    });
                    window.availableLanguages.forEach(lang => {
                        $("#edit-category-form [name='name[" + lang + "]']").val(response.data.name?.[lang] ?? '');
                        $("#edit-category-form [name='description[" + lang + "]']").val(response.data.description?.[lang] ?? '');
                    });
                    $("#edit-category-form [name = 'status']").val(response.data.status).trigger('change');
                    $("#edit-category-form [name = 'slug']").val(response.data.slug);
                    $("#edit-category-form [name = 'sort_order']").val(response.data.sort_order);
                    $("#edit-category-form #category-image").attr('src', response.data.image);;
                    let actionUrl = `categories/${response.data.id}`;
                    $("#edit-category-form").attr('action', actionUrl);
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
    // update category
    $(document).on('click', '#update-category', function (e) {
        e.preventDefault();
        let form = $('#edit-category-form');
        let getUrl = form.attr('action');
        let data = new FormData(form[0]);
        data.append('_method', 'PUT');
        let modal = $('#edit-category');
        $.ajax({
            url: getUrl,
            type: 'post',
            data: data,
            processData: false,
            contentType: false,

            success: function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    $('#categories-table').DataTable().ajax.reload(null, false);
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
                        const inputField = $(`#edit-category-form [name="${fieldName}"]`);

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
    // load category to product
    $('#create-product').on('shown.bs.modal', function () {
        let modal = $(this);
        let url = modal.data('load-category-url');
        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                if (response.status == "success") {


                    modal.find('#load-category').html(response.html);
                    modal.find('#load-category .select2').select2({
                        placeholder: "Select Category",
                        allowClear: true,
                        dropdownParent: modal
                    });
                }
            }
        });
    });
    // store product
    $(document).on('click', '#store-product', function (e) {
        e.preventDefault();
        let form = $('#create-product-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-product');
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
                    $("#products-table").DataTable().ajax.reload(null, false);
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

                        const inputField = $(`#create-product-form [name="${fieldName}"]`);

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
    // Edit product
    $(document).on('click', '.edit-product-btn', function (e) {
        e.preventDefault();
        let getUrl = $(this).attr("href");
        let modal = $('#edit-product');
        $.ajax({
            url: getUrl,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {
                    window.availableLanguages.forEach(lang => {
                        $("#edit-product-form [name='name[" + lang + "]']").val(response.data.name?.[lang] ?? '');
                        $("#edit-product-form [name='short_description[" + lang + "]']").val(response.data.short_description?.[lang] ?? '');
                        $("#edit-product-form [name='long_description[" + lang + "]']").val(response.data.long_description?.[lang] ?? '');
                    });
                    // load category
                    modal.find('#load-edit-category').html(response.html);
                    modal.find('#load-edit-category .select2').select2({
                        placeholder: "Select Category",
                        allowClear: true,
                        dropdownParent: modal
                    });
                    $("#edit-product-form [name='sku']").val(response.data.sku);
                    $("#edit-product-form [name='weight']").val(response.data.weight);
                    $("#edit-product-form [name='length']").val(response.data.length);
                    $("#edit-product-form [name='width']").val(response.data.width);
                    $("#edit-product-form [name='height']").val(response.data.height);
                    $("#edit-product-form [name='type']").val(response.data.type).trigger('change');
                    $("#edit-product-form [name='status']").val(response.data.status).trigger('change');

                    let actionUrl = `products/${response.data.id}`;
                    $("#edit-product-form").attr('action', actionUrl);
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
    // update product
    $(document).on('click', '#update-product', function (e) {
        e.preventDefault();
        console.log("just for test");

        let form = $('#edit-product-form');
        let getUrl = form.attr('action');
        let data = new FormData(form[0]);
        data.append('_method', 'PUT');
        let modal = $('#edit-product');
        $.ajax({
            url: getUrl,
            type: 'post',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    $('#products-table').DataTable().ajax.reload(null, false);
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
                        const inputField = $(`#edit-product-form [name="${fieldName}"]`);

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
    $('#create-product-image').on('shown.bs.modal', function () {
        let modal = $(this);
        modal.find('.select2').select2({
            placeholder: "Select product",
            allowClear: true,
            dropdownParent: modal
        });

    });
    // store product image
    $(document).on('click', '#store-product-image', function (e) {
        e.preventDefault();
        let form = $('#create-product-image-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-product-image');
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
                    window.location.reload(true);
                }

            }, error: function (xhr) {
                btn.prop('disabled', false);

                if (xhr.status === 422) {
                    modal.modal('show');
                    $('.text-danger').remove(); // Clear all old error messages

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        // Convert dot notation to array format: address.en => address[en]
                        if (key.includes('.')) {
                            const parts = key.split('.');
                            fieldName = parts.shift() + '[' + parts.join('][') + ']';
                        } else if (key == 'image') {
                            fieldName = 'image[]';
                        } else {
                            fieldName = key;
                        }

                        // Select by name attribute

                        const inputField = $(`#create-product-image-form [name="${fieldName}"]`);

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
    // Delete product image delete
    $(document).on('click', '.product-image-delete', function (e) {
        e.preventDefault();

        if (!confirm('Are you sure you want to delete this image?')) {
            return false;
        }

        let getHref = $(this).attr('href');
        let imageId = $(this).data('image-id');

        $.ajax({
            url: getHref,
            type: 'DELETE', // better uppercase
            success: function (response) {
                if (response.status === "success") {
                    $('#image-' + imageId).remove();
                    toastr.success(response.message);
                } else {
                    toastr.error("Failed to delete image.");
                }
            },
            error: function () {
                toastr.error("Some Issue");
            }
        });
    });
    // store slider
    $(document).on('click', '#store-attribute', function (e) {
        e.preventDefault();
        let form = $('#create-attribute-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-attribute');
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
                    $("#attributes-table").DataTable().ajax.reload(null, false);
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

                        const inputField = $(`#create-attribute-form [name="${fieldName}"]`);

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
    // edit attribute
    $(document).on('click', '.edit-attribute-btn', function (e) {
        e.preventDefault();
        let getUrl = $(this).attr("href");
        $.ajax({
            url: getUrl,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {
                    console.log(response.data);

                    window.availableLanguages.forEach(lang => {
                        $("#edit-attribute-form [name='name[" + lang + "]']").val(response.data.name?.[lang] ?? '');
                    });
                    let actionUrl = `attributes/${response.data.id}`;
                    $("#edit-attribute-form").attr('action', actionUrl);
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
    // update attribute
    $(document).on('click', '#update-attribute', function (e) {
        e.preventDefault();
        let form = $('#edit-attribute-form');
        let getUrl = form.attr('action');
        let data = new FormData(form[0]);
        data.append('_method', 'PUT');
        let modal = $('#edit-attribute');
        $.ajax({
            url: getUrl,
            type: 'post',
            data: data,
            processData: false,
            contentType: false,

            success: function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    $('#attributes-table').DataTable().ajax.reload(null, false);
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
                        const inputField = $(`#edit-attribute-form [name="${fieldName}"]`);

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
    // call select 2 on attribute value
    $('#create-attribute-value').on('shown.bs.modal', function () {
        let modal = $(this);

        modal.find('.select2').select2({
            placeholder: "Select attribute",
            allowClear: true,
            dropdownParent: modal
        });
    });
    // store attribute value
    $(document).on('click', '#store-attribute-value', function (e) {
        e.preventDefault();
        let form = $('#create-attribute-value-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-attribute-value');
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
                    $("#attributevalues-table").DataTable().ajax.reload(null, false);
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

                        const inputField = $(`#create-attribute-value-form [name="${fieldName}"]`);

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
    // edit attribute value
    $(document).on('click', '.edit-attribute-value-btn', function (e) {
        e.preventDefault();
        let getUrl = $(this).attr("href");
        $.ajax({
            url: getUrl,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {
                    console.log(response.data);

                    window.availableLanguages.forEach(lang => {
                        $("#edit-attribute-value-form [name='value[" + lang + "]']").val(response.data.value?.[lang] ?? '');
                    });
                    let select = $('#edit-attribute-value-form [name = "attribute_id"]');
                    select.empty()
                    response.attributes.forEach(attribute => {
                        let selected = attribute.id == response.data.attribute_id ? 'selected' : '';
                        select.append(`<option value="${attribute.id}" ${selected}>${attribute.name.en}</option>`)
                    });
                    $("#edit-attribute-value-form [name='slug']").val(response.data.slug);
                    $("#edit-attribute-value-form [name='sort_order']").val(response.data.sort_order);
                    let actionUrl = `attribute-values/${response.data.id}`;
                    $("#edit-attribute-value-form").attr('action', actionUrl);
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
    // update attribute value
    $(document).on('click', '#update-attribute-value', function (e) {
        e.preventDefault();
        let form = $('#edit-attribute-value-form');
        let getUrl = form.attr('action');
        let data = new FormData(form[0]);
        data.append('_method', 'PUT');
        let modal = $('#edit-attribute-value');
        $.ajax({
            url: getUrl,
            type: 'post',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    $('#attributevalues-table').DataTable().ajax.reload(null, false);
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
                        const inputField = $(`#edit-attribute-value-form [name="${fieldName}"]`);

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
    // store product variant
    $(document).on('click', '#store-product-variant', function (e) {
        e.preventDefault();
        let form = $('#create-product-variant-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-product-variant');
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
                    $("#productvariants-table").DataTable().ajax.reload(null, false);
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

                        const inputField = $(`#create-product-variant-form [name="${fieldName}"]`);

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
    // Edit product variant
    $(document).on('click', '.edit-product-variant-btn', function (e) {
        e.preventDefault();
        let getUrl = $(this).attr("href");
        let modal = $('#edit-product-variant');
        $.ajax({
            url: getUrl,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {
                    modal.find('#attribute-value-select').html(response.html);
                    let select = $('#edit-product-variant [name="product_id"]');
                    select.empty();
                    response.products.forEach(product => {
                        let selected = product.id == response.data.product_id ? 'selected' : '';
                        select.append(`<option value="${product.id}" ${selected}>${product.name.en}</option>`)
                    });
                    $('#edit-product-variant [name="sku"]').val(response.data.sku);
                    $('#edit-product-variant [name="price"]').val(response.data.price);
                    $('#edit-product-variant [name="barcode"]').val(response.data.barcode);
                    $('#edit-product-variant [name="compare_price"]').val(response.data.compare_price);
                    $('#edit-product-variant [name="cost_price"]').val(response.data.cost_price);
                    $('#edit-product-variant [name="qty"]').val(response.data.qty);
                    $('#edit-product-variant [name="min_order_qty"]').val(response.data.min_order_qty);
                    $('#edit-product-variant [name="max_order_qty"]').val(response.data.max_order_qty);
                    $('#edit-product-variant [name="is_track_stock"]').val(response.data.is_track_stock);
                    let actionUrl = `product-variants/${response.data.id}`;
                    $("#edit-product-variant-form").attr('action', actionUrl);
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
    // update product variant
    $(document).on('click', '#update-product-variant', function (e) {
        e.preventDefault();
        let form = $('#edit-product-variant-form');
        let getUrl = form.attr('action');
        let data = new FormData(form[0]);
        data.append('_method', 'PUT');
        let modal = $('#edit-product-variant');
        $.ajax({
            url: getUrl,
            type: 'post',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    $('#productvariants-table').DataTable().ajax.reload(null, false);
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
                        const inputField = $(`#edit-product-variant-form [name="${fieldName}"]`);

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
    // Store stock
    $(document).on('click', '#store-stock', function (e) {
        e.preventDefault();
        let form = $('#create-stock-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-stock');
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
                    $("#stocks-table").DataTable().ajax.reload(null, false);
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

                        const inputField = $(`#create-stock-form [name="${fieldName}"]`);

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
    // edit stock
    $(document).on('click', '.edit-stock-btn', function (e) {
        e.preventDefault();
        let getUrl = $(this).attr("href");
        $.ajax({
            url: getUrl,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {
                    console.log(response);

                    let select = $("#edit-stock-form [name='product_variant_id']");
                    let selectCurrency = $("#edit-stock-form [name='currency_id']");

                    select.empty();
                    response.productVariants.forEach(productVariant => {
                        let selected = response.data.product_variant_id == productVariant.id ? 'selected' : '';
                        select.append(`<option value="${productVariant.id}" ${selected}>${productVariant.product.name.en}</option>`)
                    });
                    response.currencies.forEach(currency => {
                        let currencySelected = response.data.currency_id == currency.id ? 'selected' : '';
                        selectCurrency.append(`<option value="${currency.id}" ${currencySelected}>${currency.code}</option>`)
                    });
                    $("#edit-stock-form [name='stock_status']").val(response.data.stock_status).trigger('change');
                    $("#edit-stock-form [name='qty']").val(response.data.qty);


                    let actionUrl = `stock/${response.data.id}`;
                    $("#edit-stock-form").attr('action', actionUrl);
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
    // Update stock
    $(document).on('click', '#update-stock', function (e) {
        e.preventDefault();
        let form = $('#edit-stock-form');
        let getUrl = form.attr('action');
        let data = new FormData(form[0]);
        data.append('_method', 'PUT');
        let modal = $('#edit-stock');
        $.ajax({
            url: getUrl,
            type: 'post',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    $('#stocks-table').DataTable().ajax.reload(null, false);
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
                        const inputField = $(`#edit-stock-form [name="${fieldName}"]`);
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
    // Store currency
    $(document).on('click', '#store-currency', function (e) {
        e.preventDefault();
        let form = $('#create-currency-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-currency');
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
                    $("#currencies-table").DataTable().ajax.reload(null, false);
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

                        const inputField = $(`#create-currency-form [name="${fieldName}"]`);

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
    // edit currency
    $(document).on('click', '.edit-currency-btn', function (e) {
        e.preventDefault();
        let getUrl = $(this).attr("href");
        $.ajax({
            url: getUrl,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {
                    $("#edit-currency-form [name='is_default']").val(response.data.is_default).trigger('change');
                    $("#edit-currency-form [name='code']").val(response.data.code);
                    $("#edit-currency-form [name='symbol']").val(response.data.symbol);
                    $("#edit-currency-form [name='exchange_rate']").val(response.data.exchange_rate);

                    let actionUrl = `currencies/${response.data.id}`;
                    $("#edit-currency-form").attr('action', actionUrl);
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
    // Update currency
    $(document).on('click', '#update-currency', function (e) {
        e.preventDefault();
        let form = $('#edit-currency-form');
        let getUrl = form.attr('action');
        let data = new FormData(form[0]);
        data.append('_method', 'PUT');
        let modal = $('#edit-currency');
        $.ajax({
            url: getUrl,
            type: 'post',
            data: data,
            processData: false,
            contentType: false,

            success: function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    $('#currencies-table').DataTable().ajax.reload(null, false);
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
                        const inputField = $(`#edit-currency-form [name="${fieldName}"]`);

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
    // store coupon
    $(document).on('click', '#store-coupon', function (e) {
        e.preventDefault();
        let form = $('#create-coupon-form');
        let data = new FormData(form[0]);
        let btn = $(this);
        let getUrl = form.attr('action');
        let modal = $('#create-coupon');
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
                    $("#coupons-table").DataTable().ajax.reload(null, false);
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

                        const inputField = $(`#create-coupon-form [name="${fieldName}"]`);

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
    // edit stock
    $(document).on('click', '.edit-coupon-btn', function (e) {
        e.preventDefault();
        let getUrl = $(this).attr("href");
        $.ajax({
            url: getUrl,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {
                    $("#edit-coupon-form [name='name']").val(response.data.name);
                    $("#edit-coupon-form [name='discount']").val(response.data.discount);
                    $("#edit-coupon-form [name='valid_until']").val(response.data.valid_until);
                    let actionUrl = `coupons/${response.data.id}`;
                    $("#edit-coupon-form").attr('action', actionUrl);
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
    // Update coupon
    $(document).on('click', '#update-coupon', function (e) {
        e.preventDefault();
        let form = $('#edit-coupon-form');
        let getUrl = form.attr('action');
        let data = new FormData(form[0]);
        data.append('_method', 'PUT');
        let modal = $('#edit-coupon');
        $.ajax({
            url: getUrl,
            type: 'post',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == "success") {
                    toastr.success(response.message);
                    $('#coupons-table').DataTable().ajax.reload(null, false);
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
                        const inputField = $(`#edit-coupon-form [name="${fieldName}"]`);
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
    // update delivered_at
    $(document).on('click', '#updateDeliveredAtDate', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'get',
            success: function (response) {
                if (response.status == "success") {
                    $('#orders-table').DataTable().ajax.reload(null, false);
                    toastr.success(response.message);
                }
            },
            error: function (xhr) {
                console.log(xhr);

            }
        });



    })








})
