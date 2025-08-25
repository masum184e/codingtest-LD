
<table id="datatable" class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
</table>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editUserId">
                <div class="mb-3">
                    <label for="editUserName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="editUserName">
                </div>
                <div class="mb-3">
                    <label for="editUserEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="editUserEmail">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateUserBtn">Update</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Toastr CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function () {
    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("users.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', '.edit-btn', function () {
        $('#editUserId').val($(this).data('id'));
        $('#editUserName').val($(this).data('name'));
        $('#editUserEmail').val($(this).data('email'));
        $('#editUserModal').modal('show');
    });

    // Update user via AJAX
    $('#updateUserBtn').click(function () {
        var id    = $('#editUserId').val();
        var name  = $('#editUserName').val();
        var email = $('#editUserEmail').val();

        $.ajax({
            url: '/users/' + id,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: name,
                email: email
            },
            success: function (res) {
                $('#editUserModal').modal('hide');
                table.ajax.reload(null, false);

                if(res.status === 'success'){
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                }
            },
            error: function (xhr) {
                toastr.error('Something went wrong!');
            }
        });
    });
});
</script>
@endpush
