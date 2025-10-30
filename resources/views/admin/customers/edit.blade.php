@extends('layouts.admin_master')

@section('title', 'Edit Customer: ' . $customer->name)

@section('content')
<div class="container">
    <div class="card shadow-lg rounded-3 border-0 mt-5">
        <div class="card-header bg-warning text-white rounded-top-3">
            <h2 class="h4 mb-0"><i class="fas fa-user-edit me-2"></i> Edit Customer Details</h2>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $customer->name) }}" 
                           required 
                           placeholder="Enter customer's full name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $customer->email) }}" 
                           required 
                           placeholder="Enter unique email address">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label fw-bold">Phone Number</label>
                    <input type="tel" 
                           class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $customer->phone) }}" 
                           placeholder="Optional: Enter phone number">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sync-alt me-1"></i> Update Customer
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
