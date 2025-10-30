@extends('layouts.admin_master')

@section('title', 'Order Details #'.$order->id)

@section('content')
<div class="container-fluid pt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order #{{ $order->id }} Details</h5>
                    <div class="btn-group">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    
                    {{-- Order Header Details --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user-circle me-2 text-primary"></i>Customer Information</h6>
                            <hr class="mt-1">
                            <p class="mb-1"><strong>Name:</strong> {{ $order->customer->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->customer->email }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $order->customer->phone }}</p>
                            <p class="mb-1"><strong>Address:</strong> {{ $order->customer->address }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-info-circle me-2 text-primary"></i>Order Summary</h6>
                            <hr class="mt-1">
                            <p class="mb-1"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i A') }}</p>
                            <p class="mb-1">
                                <strong>Status:</strong> 
                                @php
                                    $badgeClass = [
                                        'Pending' => 'bg-warning text-dark',
                                        'Completed' => 'bg-success',
                                        'Cancelled' => 'bg-danger'
                                    ][$order->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $order->status }}</span>
                            </p>
                            <p class="mb-1"><strong>Total Items:</strong> {{ $order->items->sum('quantity') }}</p>
                            <h4 class="mt-3 text-success">
                                <strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}
                            </h4>
                        </div>
                    </div>

                    {{-- Order Items Table --}}
                    <h6 class="mt-5"><i class="fas fa-cubes me-2 text-primary"></i>Products Ordered</h6>
                    <hr class="mt-1">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $item->product->name }} 
                                        <small class="text-muted d-block">SKU: {{ $item->product->sku }}</small>
                                    </td>
                                    <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end text-success h5"><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
