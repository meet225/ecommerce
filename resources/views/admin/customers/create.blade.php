@extends('layouts.admin_master')

@section('title', 'Create New Customer')

@section('content')
<div class="container">
    <div class="card shadow-lg rounded-3 border-0 mt-5">
        <div class="card-header bg-primary text-white rounded-top-3">
            <h2 class="h4 mb-0"><i class="fas fa-user-plus me-2"></i> Add New Customer</h2>
        </div>
        <div class="card-body p-4">
            
            <form id="customer-form" action="{{ route('admin.customers.store') }}" method="POST" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                    <input type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        placeholder="Enter customer's full name" 
                        required>
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
                        value="{{ old('email') }}" 
                        placeholder="Enter unique email address" 
                        required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                    <input type="tel" 
                        class="form-control @error('phone') is-invalid @enderror" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}" 
                        placeholder="Enter 10-digit phone number"
                        required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Save Customer
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('customer-form');
    const constraints = {
        name: {
            presence: { allowEmpty: false, message: "^Name is required." },
            format: {
                pattern: "^[A-Za-z\\s]+$",
                message: "^Name can only contain letters and spaces."
            },
            length: {
                maximum: 100,
                message: "^Name cannot be longer than 100 characters."
            }
        },
        email: {
            presence: { allowEmpty: false, message: "^Email is required." },
            email: { message: "^Enter a valid email address." },
            length: { maximum: 255, message: "^Email cannot exceed 255 characters." }
        },
        phone: {
            presence: { allowEmpty: false, message: "^Phone number is required." },
            format: {
                pattern: "^[0-9]{10}$",
                message: "^Phone number must be exactly 10 digits."
            }
        }
    };
    function clearErrors() {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback.client-error').forEach(el => el.remove());
    }

    function showErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback client-error';
                feedback.textContent = errors[field][0];
                input.parentNode.appendChild(feedback);
            }
        });
    }

    form.querySelectorAll('input').forEach(input => {
        input.addEventListener('blur', function () {
            const fieldName = this.name;
            const value = {};
            value[fieldName] = this.value;

            const result = validate(value, { [fieldName]: constraints[fieldName] });
            this.classList.remove('is-invalid');
            const oldError = this.parentNode.querySelector('.invalid-feedback.client-error');
            if (oldError) oldError.remove();
            if (result) {
                this.classList.add('is-invalid');
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback client-error';
                feedback.textContent = result[fieldName][0];
                this.parentNode.appendChild(feedback);
            }
        });
    });

    form.addEventListener('submit', function (event) {
        clearErrors();
        const errors = validate(form, constraints, { fullMessages: false });

        if (errors) {
            event.preventDefault();
            showErrors(errors);
            const firstError = form.querySelector('.is-invalid');
            if (firstError) firstError.focus();
        }
    });
});
</script>
@endpush
