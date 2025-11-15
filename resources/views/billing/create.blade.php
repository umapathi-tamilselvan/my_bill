@extends('layouts.app')

@section('title', 'Create Bill')
@section('page-title', 'POS / Create Bill')

@section('content')
    <form id="billForm" action="{{ route('billing.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Bill Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-select 
                                    name="customer_id" 
                                    label="Customer (Optional)" 
                                    :options="$customers->pluck('name', 'id')->toArray()"
                                    value="{{ old('customer_id') }}"
                                />
                            </div>
                            <div class="col-md-6">
                                <x-input name="bill_date" label="Bill Date" type="date" value="{{ old('bill_date', date('Y-m-d')) }}" required />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Products</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addProductRow()">
                            <i class="bi bi-plus"></i> Add Product
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="productsTable">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Tax %</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="productsTableBody">
                                    <!-- Products will be added here dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Subtotal:</strong> <span id="subtotal">0.00</span>
                        </div>
                        <div class="mb-2">
                            <strong>Tax:</strong> <span id="taxAmount">0.00</span>
                        </div>
                        <div class="mb-2">
                            <strong>Discount:</strong> <span id="discountAmount">0.00</span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <strong>Total:</strong> <span id="totalAmount" class="h4">0.00</span>
                        </div>
                        <x-input name="paid_amount" label="Paid Amount" type="number" step="0.01" value="{{ old('paid_amount', 0) }}" />
                        <x-select 
                            name="payment_status" 
                            label="Payment Status" 
                            :options="['paid' => 'Paid', 'partial' => 'Partial', 'unpaid' => 'Unpaid']"
                            value="{{ old('payment_status', 'paid') }}"
                        />
                        <x-input name="notes" label="Notes" type="textarea" value="{{ old('notes') }}" />
                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            <i class="bi bi-save"></i> Save Bill
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Product Search Modal -->
    <x-modal id="productSearchModal" title="Search Product" size="lg">
        <div class="mb-3">
            <input type="text" class="form-control" id="productSearchInput" placeholder="Search by name, code, or barcode...">
        </div>
        <div id="productSearchResults" class="list-group" style="max-height: 400px; overflow-y: auto;">
            <!-- Search results will appear here -->
        </div>
    </x-modal>

    @push('scripts')
    <script>
        let productRowIndex = 0;
        let currentSearchRow = null;

        function addProductRow() {
            const tbody = document.getElementById('productsTableBody');
            const row = document.createElement('tr');
            row.id = `productRow_${productRowIndex}`;
            row.innerHTML = `
                <td>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="searchProduct(${productRowIndex})">
                        <i class="bi bi-search"></i> Select Product
                    </button>
                    <input type="hidden" name="items[${productRowIndex}][product_id]" id="product_id_${productRowIndex}">
                    <div id="product_info_${productRowIndex}"></div>
                </td>
                <td><input type="number" name="items[${productRowIndex}][quantity]" class="form-control form-control-sm" value="1" min="1" onchange="calculateRow(${productRowIndex})" required></td>
                <td><input type="number" name="items[${productRowIndex}][unit_price]" class="form-control form-control-sm" step="0.01" min="0" onchange="calculateRow(${productRowIndex})" required></td>
                <td><input type="number" name="items[${productRowIndex}][tax_rate]" class="form-control form-control-sm" step="0.01" min="0" max="100" value="0" onchange="calculateRow(${productRowIndex})"></td>
                <td><input type="number" name="items[${productRowIndex}][discount_amount]" class="form-control form-control-sm" step="0.01" min="0" value="0" onchange="calculateRow(${productRowIndex})"></td>
                <td><span id="row_total_${productRowIndex}">0.00</span></td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(${productRowIndex})"><i class="bi bi-trash"></i></button></td>
            `;
            tbody.appendChild(row);
            productRowIndex++;
        }

        function searchProduct(rowIndex) {
            currentSearchRow = rowIndex;
            const modal = new bootstrap.Modal(document.getElementById('productSearchModal'));
            modal.show();
            document.getElementById('productSearchInput').value = '';
            document.getElementById('productSearchInput').focus();
        }

        document.getElementById('productSearchInput').addEventListener('input', function(e) {
            const term = e.target.value;
            if (term.length < 2) {
                document.getElementById('productSearchResults').innerHTML = '';
                return;
            }

            fetch(`{{ route('api.billing.products.search') }}?q=${encodeURIComponent(term)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(products => {
                const resultsDiv = document.getElementById('productSearchResults');
                resultsDiv.innerHTML = '';
                
                if (products.length === 0) {
                    resultsDiv.innerHTML = '<div class="list-group-item">No products found</div>';
                    return;
                }

                products.forEach(product => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'list-group-item list-group-item-action';
                    item.innerHTML = `
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>${product.name}</strong> (${product.code})<br>
                                <small>Price: ${product.price} | Stock: ${product.stock} ${product.unit}</small>
                            </div>
                        </div>
                    `;
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        selectProduct(currentSearchRow, product);
                        bootstrap.Modal.getInstance(document.getElementById('productSearchModal')).hide();
                    });
                    resultsDiv.appendChild(item);
                });
            });
        });

        function selectProduct(rowIndex, product) {
            document.getElementById(`product_id_${rowIndex}`).value = product.id;
            document.getElementById(`product_info_${rowIndex}`).innerHTML = `
                <strong>${product.name}</strong><br>
                <small>Code: ${product.code} | Stock: ${product.stock}</small>
            `;
            document.querySelector(`input[name="items[${rowIndex}][unit_price]"]`).value = product.price;
            document.querySelector(`input[name="items[${rowIndex}][tax_rate]"]`).value = product.tax_rate || 0;
            calculateRow(rowIndex);
        }

        function calculateRow(rowIndex) {
            const qty = parseFloat(document.querySelector(`input[name="items[${rowIndex}][quantity]"]`).value) || 0;
            const price = parseFloat(document.querySelector(`input[name="items[${rowIndex}][unit_price]"]`).value) || 0;
            const taxRate = parseFloat(document.querySelector(`input[name="items[${rowIndex}][tax_rate]"]`).value) || 0;
            const discount = parseFloat(document.querySelector(`input[name="items[${rowIndex}][discount_amount]"]`).value) || 0;

            const subtotal = qty * price;
            const subtotalAfterDiscount = subtotal - discount;
            const tax = subtotalAfterDiscount * (taxRate / 100);
            const total = subtotalAfterDiscount + tax;

            document.getElementById(`row_total_${rowIndex}`).textContent = total.toFixed(2);
            calculateTotal();
        }

        function calculateTotal() {
            let subtotal = 0;
            let totalTax = 0;
            let totalDiscount = 0;

            for (let i = 0; i < productRowIndex; i++) {
                const row = document.getElementById(`productRow_${i}`);
                if (!row) continue;

                const qty = parseFloat(document.querySelector(`input[name="items[${i}][quantity]"]`)?.value) || 0;
                const price = parseFloat(document.querySelector(`input[name="items[${i}][unit_price]"]`)?.value) || 0;
                const taxRate = parseFloat(document.querySelector(`input[name="items[${i}][tax_rate]"]`)?.value) || 0;
                const discount = parseFloat(document.querySelector(`input[name="items[${i}][discount_amount]"]`)?.value) || 0;

                const rowSubtotal = qty * price;
                const rowSubtotalAfterDiscount = rowSubtotal - discount;
                const rowTax = rowSubtotalAfterDiscount * (taxRate / 100);

                subtotal += rowSubtotal;
                totalTax += rowTax;
                totalDiscount += discount;
            }

            const total = subtotal - totalDiscount + totalTax;

            document.getElementById('subtotal').textContent = subtotal.toFixed(2);
            document.getElementById('taxAmount').textContent = totalTax.toFixed(2);
            document.getElementById('discountAmount').textContent = totalDiscount.toFixed(2);
            document.getElementById('totalAmount').textContent = total.toFixed(2);
        }

        function removeRow(rowIndex) {
            document.getElementById(`productRow_${rowIndex}`).remove();
            calculateTotal();
        }

        // Add first row on page load
        document.addEventListener('DOMContentLoaded', function() {
            addProductRow();
        });
    </script>
    @endpush
@endsection

