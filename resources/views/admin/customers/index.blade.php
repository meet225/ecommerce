@extends('layouts.admin_master')

@section('title', 'Customer List')

@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
@endpush

@push('js_core')
    <script type="text/javascript" src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
@endpush

@section('content')

<div class="container"> 
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Customer Management</h1>
        <a href="{{ route('admin.customers.create') }}" class="btn btn-success rounded-pill shadow-sm">
            <i class="fas fa-plus-circle me-2"></i> Add New Customer
        </a>
    </div>

    <div class="card shadow-lg rounded-3">
        <div class="card-body">
            <div class="table-responsive">
                <table id="customer-table" class="table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCustomerModalLabel"><i class="fas fa-trash-alt me-2"></i> Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this customer?</p>
                <p class="text-danger fw-bold">This action cannot be undone.</p>
                <input type="hidden" id="delete-id">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn"><i class="fas fa-check me-1"></i> Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var customerTable; 

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        customerTable = $('#customer-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.customers.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone', orderable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ],
            order: [[0, 'desc']], 
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            }
        });

        $(document).on('click', '.delete-customer-btn', function() {
            var customerId = $(this).data('id');
            $('#delete-id').val(customerId);
            $('#deleteCustomerModal').modal('show');
        });

        $('#confirmDeleteBtn').on('click', function() {
            var customerId = $('#delete-id').val();
            var deleteUrl = "{{ url('admin/customers') }}/" + customerId;
            
            $('#deleteCustomerModal').modal('hide');
            
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                success: function(response) {
                    $('.msg-all').prepend('<div class="alert alert-success alert-dismissible fade show" role="alert"><i class="fas fa-check-circle me-2"></i>' + response.message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    customerTable.ajax.reload(null, false);
                },
                error: function(xhr) {
                    var message = 'An unknown error occurred.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    $('.msg-all').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fas fa-exclamation-triangle me-2"></i>' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            });
        });
    });
</script>
@endpush
