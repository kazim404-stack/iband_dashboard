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

})
