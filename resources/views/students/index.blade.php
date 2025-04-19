<!DOCTYPE html>
<html>
<head>
    <title>Student CRUD - Yajra DataTable</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Student CRUD</h2>
    <button class="btn btn-success mb-3" id="addStudentBtn">Add Student</button>
    <table class="table table-bordered" id="studentTable">
        <thead>
            <tr>
                <th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Action</th>
            </tr>
        </thead>
    </table>
</div>

{{-- Modal --}}
<div class="modal fade" id="studentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="studentForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Student Form</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id" id="student_id">
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" id="name">
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" id="email">
            </div>
            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" id="phone">
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    let table = $('#studentTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('students.list') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name'},
            {data: 'email'},
            {data: 'phone'},
            {data: 'action', orderable: false, searchable: false}
        ]
    });

    let studentModal = new bootstrap.Modal($('#studentModal')[0]);

    $('#addStudentBtn').on('click', function () {
        $('#studentForm')[0].reset();
        $('#student_id').val('');
        studentModal.show();
    });

    $('#studentTable').on('click', '.editBtn', function () {
        let id = $(this).data('id');
        $.get(`/students/${id}`, function (data) {
            $('#student_id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            studentModal.show();
        });
    });

    $('#studentForm').submit(function (e) {
        e.preventDefault();
        let id = $('#student_id').val();
        let url = id ? `/students/${id}` : `{{ route('students.store') }}`;
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function () {
                studentModal.hide();
                table.ajax.reload();
            }
        });
    });

    $('#studentTable').on('click', '.deleteBtn', function () {
        if (!confirm('Are you sure?')) return;
        let id = $(this).data('id');
        $.ajax({
            url: `/students/${id}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                table.ajax.reload();
            }
        });
    });
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
</body>
</html>
