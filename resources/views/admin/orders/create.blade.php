@extends('layouts.admin_master')

@section('title', 'Create New Order')

@section('content')
<div class="container-fluid pt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Create New Order</h5>
                </div>
                <div class="card-body">
                    {{-- FIX: Corrected Blade Syntax for Action --}}
                    <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
                        @csrf

                        {{-- Hidden field for Total Amount --}}
                        <input type="hidden" name="total_amount" id="total_amount_input" value="{{ old('total_amount', $order->total_amount ?? 0) }}">

                        {{-- Customer Select --}}
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="customer_id" class="form-label required">Select Customer</label>
                                <select class="form-control @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                    <option value="" disabled selected>Choose a customer</option>
                                    @foreach($customers as $id => $name)
                                        <option value="{{ $id }}" 
                                            {{ old('customer_id', $order->customer_id ?? '') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Order Items Section --}}
                        <h6 class="mt-3"><i class="fas fa-cubes me-2 text-primary"></i>Order Items</h6>
                        <hr>
                        
                        {{-- Error message for required items --}}
                        <div id="items-required-error" class="alert alert-danger d-none" role="alert">
                            You must add at least one product to the order.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="orderItemsTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 40%">Product</th>
                                        <th style="width: 15%" class="text-center">Price</th>
                                        <th style="width: 20%" class="text-center">Quantity</th>
                                        <th style="width: 15%" class="text-end">Subtotal</th>
                                        <th style="width: 10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Dynamic rows will be inserted here --}}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end h5"><strong>Grand Total:</strong></td>
                                        <td class="text-end h5 text-success"><strong id="grand_total_display">$0.00</strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Add Product Button --}}
                        <div class="d-flex justify-content-end mb-4">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addItemButton">
                                <i class="fas fa-cart-plus me-1"></i> Add Product
                            </button>
                        </div>
                        
                        {{-- Submit Button --}}
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Create Order
                            </button>
                        </div>
                        
                        {{-- Client-Side Error for Items --}}
                        @if ($errors->has('products') || $errors->has('products.*.quantity'))
                            <div class="alert alert-danger mt-3">
                                Please check the items section: {{ $errors->first('products') ?? $errors->first('products.*.quantity') }}
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Since this view is only for 'create', we don't need the edit logic branches in JS
        let allProducts = @json($products);
        // $order is null in create view, but we keep the structure safe
        let orderItems = []; 
        let itemCounter = 0;

        // Function to check if at least one item is present
        function checkItemCount() {
            const rowCount = $('#orderItemsTable tbody tr').length;
            const errorDiv = $('#items-required-error');
            
            if (rowCount === 0) {
                errorDiv.removeClass('d-none');
                return false;
            } else {
                errorDiv.addClass('d-none');
                return true;
            }
        }

        /**
         * Renders a product select dropdown.
         * @param {string} selectedId - The product ID to pre-select.
         */
        function renderProductSelect(selectedId = '') {
            let options = '<option value="" data-price="0" data-stock="0" disabled selected>Select Product</option>';
            
            allProducts.forEach(product => {
                let isSelected = product.id == selectedId ? 'selected' : '';
                options += `<option value="${product.id}" data-price="${product.price}" data-stock="${product.stock_quantity}" ${isSelected}>
                                ${product.name} (Stock: ${product.stock_quantity})
                            </option>`;
            });

            return `
                <select name="products[${itemCounter}][product_id]" 
                        class="form-select product-select" 
                        data-counter="${itemCounter}" 
                        required>
                    ${options}
                </select>
                <input type="hidden" name="products[${itemCounter}][name]" class="product-name-input">
            `;
        }
        
        /**
         * Adds a new item row to the table.
         * @param {object} itemData - Data for editing (optional).
         */
        function addItemRow(itemData = {}) {
            // In create mode, effective stock is just the current stock
            let effectiveMaxStock = 0;
            let currentPrice = 0;
            let subtotal = 0;
            let quantity = 1;
            let productId = itemData.product_id ?? '';
            
            // If the itemData has a product_id (e.g., from old input restoration), calculate initial values
            if (productId) {
                let product = allProducts.find(p => p.id == productId);
                currentPrice = product ? product.price : 0;
                effectiveMaxStock = product ? product.stock_quantity : 0;
                quantity = itemData.quantity ?? 1;
                subtotal = currentPrice * quantity;
            } else {
                // Default new row has 0 stock/price until product is selected
                effectiveMaxStock = 0; 
                currentPrice = 0;
                subtotal = 0;
                quantity = 1;
            }
            
            let newRow = `<tr id="row-${itemCounter}" data-stock="${effectiveMaxStock}">
                <td>${renderProductSelect(productId)}</td>
                <td class="text-center">
                    <span class="item-price-display">$${currentPrice.toFixed(2)}</span>
                    <input type="hidden" class="item-price-input" value="${currentPrice}">
                </td>
                <td>
                    <input type="number" 
                           name="products[${itemCounter}][quantity]" 
                           value="${quantity}" 
                           class="form-control form-control-sm text-center item-quantity-input" 
                           min="1" 
                           data-counter="${itemCounter}"
                           required>
                    <small class="text-danger stock-warning d-none">Max stock: ${effectiveMaxStock}</small>
                </td>
                <td class="text-end">
                    <span class="item-subtotal-display">$${subtotal.toFixed(2)}</span>
                    <input type="hidden" class="item-subtotal-input" value="${subtotal}">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-item-btn" data-counter="${itemCounter}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
            
            $('#orderItemsTable tbody').append(newRow);
            
            // Show stock warning if initial quantity exceeds effective stock
            if(quantity > effectiveMaxStock && effectiveMaxStock > 0) {
                $(`#row-${itemCounter}`).find('.stock-warning').removeClass('d-none').text(`Quantity exceeds available stock: ${effectiveMaxStock}`);
            }

            itemCounter++;
            calculateGrandTotal();
            checkItemCount();
        }

        /**
         * Calculates the subtotal for a row and updates the grand total.
         */
        function calculateRowAndGrandTotal(row) {
            let price = parseFloat(row.find('.item-price-input').val());
            let quantity = parseInt(row.find('.item-quantity-input').val());
            let stock = parseInt(row.attr('data-stock'));
            let stockWarning = row.find('.stock-warning');
            
            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
                row.find('.item-quantity-input').val(1);
            }
            
            // Client-side stock check
            if (quantity > stock && stock > 0) {
                stockWarning.removeClass('d-none').text(`Quantity exceeds available stock: ${stock}`);
            } else {
                stockWarning.addClass('d-none');
            }

            let subtotal = price * quantity;

            row.find('.item-subtotal-display').text('$' + subtotal.toFixed(2));
            row.find('.item-subtotal-input').val(subtotal);
            
            calculateGrandTotal();
        }

        /**
         * Calculates and displays the total amount for the entire order.
         */
        function calculateGrandTotal() {
            let grandTotal = 0;
            $('.item-subtotal-input').each(function() {
                grandTotal += parseFloat($(this).val() || 0);
            });

            $('#grand_total_display').text('$' + grandTotal.toFixed(2));
            $('#total_amount_input').val(grandTotal.toFixed(2));
        }

        // --- Event Listeners ---

        // 1. Add Item Button Click
        $('#addItemButton').on('click', function() {
            addItemRow();
        });

        // 2. Remove Item Button Click (Delegated)
        $('#orderItemsTable').on('click', '.remove-item-btn', function() {
            $(this).closest('tr').remove();
            calculateGrandTotal();
            checkItemCount(); // Check if row count drops to zero
        });

        // 3. Product Selection Change (Delegated)
        $('#orderItemsTable').on('change', '.product-select', function() {
            let row = $(this).closest('tr');
            let selectedOption = $(this).find('option:selected');
            let price = parseFloat(selectedOption.data('price'));
            let stock = parseInt(selectedOption.data('stock'));
            let productName = selectedOption.text().split('(')[0].trim(); // Extract name before stock info
            
            // Update price fields
            row.find('.item-price-display').text('$' + price.toFixed(2));
            row.find('.item-price-input').val(price);

            // Update stock tracking for the row
            row.attr('data-stock', stock);
            row.find('.stock-warning').text(`Max stock: ${stock}`);
            
            // Update hidden product name input
            row.find('.product-name-input').val(productName);

            // Re-calculate row total
            calculateRowAndGrandTotal(row);
        });

        // 4. Quantity Change (Delegated)
        $('#orderItemsTable').on('input', '.item-quantity-input', function() {
            let row = $(this).closest('tr');
            calculateRowAndGrandTotal(row);
        });
        
        // 5. Form Submission Validation (Crucial for minimum items)
        $('#orderForm').on('submit', function(e) {
            if (!checkItemCount()) {
                e.preventDefault(); // Stop form submission if no items are present
                // Scroll to the error message for visibility
                $('html, body').animate({
                    scrollTop: $('#items-required-error').offset().top - 100
                }, 500);
            }
        });

        // --- Initialization ---

        // Start with one empty row, or restore old input
        if (orderItems.length > 0) {
            orderItems.forEach(item => {
                addItemRow(item);
            });
        } else {
            addItemRow();
        }
        
        // Ensure total and item count is calculated/checked on load
        calculateGrandTotal();
        checkItemCount();
    });
</script>
@endpush
