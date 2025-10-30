@extends('layouts.admin_master')

@section('title', 'Order List')

@section('content')
<div class="container-fluid pt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Orders</h5>
                    <a href="{{ route('admin.orders.create') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="fas fa-plus me-1"></i> New Order
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        {{-- The table body will be populated via AJAX by DataTables --}}
                        <table id="ordersTable" class="table table-hover table-striped w-100 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer Name</th>
                                    <th>Order Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- DataTables will populate this tbody --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Status Update Modal (Required for AJAX status update) --}}
<div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="statusUpdateModalLabel"><i class="fas fa-sync me-2"></i>Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateStatusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Change status for Order #<strong id="orderIdDisplay"></strong>:</p>
                    <select class="form-select" id="statusSelect" name="status" required>
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Include DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
{{-- Include DataTables JS and jQuery (Required for DataTables) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function () {
        // Initialize DataTables with AJAX
        var table = $('#ordersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.orders.index') }}", // Points to your OrderController@index method
            columns: [
                { data: 'id', name: 'id' },
                { data: 'customer_name', name: 'customer_name' },
                // Note: The 'order_date' column is mapped to 'created_at' in the controller snippet
                { data: 'order_date', name: 'order_date' }, 
                { data: 'total_amount_formatted', name: 'total_amount' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'num_items', name: 'num_items', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            // Default sorting: Newest first (Orders are sorted by created_at descending in the controller)
            order: [
                [ 2, 'desc' ] // Sort by 'Order Date' column (index 2) descending
            ]
        });

        // --- Status Update Modal Logic ---

        // 1. Show Modal when 'Update Status' button is clicked
        $('#ordersTable').on('click', '.update-status-btn', function() {
            var orderId = $(this).data('id');
            var currentStatus = $(this).data('status');
            
            // Set values in the modal
            $('#orderIdDisplay').text(orderId);
            $('#statusSelect').val(currentStatus);
            
            // Set the form action dynamically
            var route = '{{ route("admin.orders.updateStatus", ":id") }}';
            route = route.replace(':id', orderId);
            $('#updateStatusForm').attr('action', route);

            // Show the modal
            var statusUpdateModal = new bootstrap.Modal(document.getElementById('statusUpdateModal'));
            statusUpdateModal.show();
        });

        // 2. Handle form submission (Optional: could use the simplified PUT in the blade, but modal is better)
        $('#updateStatusForm').on('submit', function(e) {
            e.preventDefault();
            
            // Simple AJAX submission for status update
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST', // Method is PUT, but AJAX uses POST with _method
                data: $(this).serialize(),
                success: function(response) {
                    // Hide the modal and refresh the table
                    var statusUpdateModal = bootstrap.Modal.getInstance(document.getElementById('statusUpdateModal'));
                    statusUpdateModal.hide();
                    table.ajax.reload(null, false); // Reload table data without resetting pagination
                    // Show a success message (you'd need a toast or flash message system here)
                    alert('Status updated successfully!'); 
                },
                error: function(xhr) {
                    alert('Error updating status: ' + xhr.responseJSON.message);
                }
            });
        });
    });
</script>
@endpush
