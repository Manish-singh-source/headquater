@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('invoices') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('invoices-details', $invoice->id) }}">Invoice Details</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Invoice</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Edit Invoice: {{ $invoice->invoice_number }}</h5>

                    <form id="invoiceUpdateForm" action="{{ route('invoice.update', $invoice->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="return_warehouse_id" id="quantityReturnWarehouseId">
                        <input type="hidden" name="return_remark" id="quantityReturnRemarkValue">

                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Invoice Date</label>
                                <input type="date" class="form-control"
                                    value="{{ old('invoice_date', optional($invoice->invoice_date)->format('Y-m-d')) }}"
                                    disabled>
                                <input type="hidden" name="invoice_date"
                                    value="{{ old('invoice_date', optional($invoice->invoice_date)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">PO Number</label>
                                <input type="text" class="form-control"
                                    value="{{ old('po_number', $invoice->po_number) }}" disabled>
                                <input type="hidden" name="po_number"
                                    value="{{ old('po_number', $invoice->po_number) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">PO Date</label>
                                <input type="date" class="form-control"
                                    value="{{ old('po_date', optional($invoice->po_date)->format('Y-m-d')) }}"
                                    disabled>
                                <input type="hidden" name="po_date"
                                    value="{{ old('po_date', optional($invoice->po_date)->format('Y-m-d')) }}">
                            </div>
                            {{-- <div class="col-md-3">
                                <label class="form-label">Round Off</label>
                                <input type="number" step="0.01" class="form-control" name="round_off"
                                    value="{{ old('round_off', $invoice->round_off ?? 0) }}">
                            </div> --}}
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width:180px;">Item</th>
                                        <th>HSN</th>
                                        <th>Qty</th>
                                        <th>Box</th>
                                        <th>Weight</th>
                                        <th>Unit Price</th>
                                        <th>Discount</th>
                                        <th>Tax %</th>
                                        <th style="width:72px;">Action</th>
                                        {{-- <th>Description</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->details as $index => $detail)
                                        <tr>
                                            <td>
                                                <strong>{{ $detail->tempOrder?->item_code ?? $detail->item_code }}</strong><br>
                                                <small class="text-muted">{{ $detail->product->sku ?? '-' }}</small><br>
                                                <span>{{ $detail->tempOrder?->description ?? $detail->product?->brand_title }}</span>
                                                <input type="hidden" name="details[{{ $index }}][id]"
                                                    value="{{ $detail->id }}">
                                            </td>
                                            <td>
                                                <span>{{ old("details.$index.hsn", $detail->hsn ?? $detail->tempOrder?->hsn) }}</span>
                                                <input type="hidden" name="details[{{ $index }}][hsn]"
                                                    value="{{ old("details.$index.hsn", $detail->hsn ?? $detail->tempOrder?->hsn) }}">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0" class="form-control"
                                                    name="details[{{ $index }}][quantity]"
                                                    value="{{ old("details.$index.quantity", $detail->quantity) }}"
                                                    data-initial-quantity="{{ $detail->quantity }}" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0" class="form-control"
                                                    name="details[{{ $index }}][box_count]"
                                                    value="{{ old("details.$index.box_count", $detail->box_count ?? 0) }}">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0" class="form-control"
                                                    name="details[{{ $index }}][weight]"
                                                    value="{{ old("details.$index.weight", $detail->weight ?? 0) }}">
                                            </td>
                                            <td>
                                                <span>{{ number_format(old("details.$index.unit_price", $detail->unit_price), 2) }}</span>
                                                <input type="hidden" name="details[{{ $index }}][unit_price]"
                                                    value="{{ old("details.$index.unit_price", $detail->unit_price) }}">
                                            </td>
                                            <td>
                                                <span>{{ number_format(old("details.$index.discount", $detail->discount ?? 0), 2) }}</span>
                                                <input type="hidden" name="details[{{ $index }}][discount]"
                                                    value="{{ old("details.$index.discount", $detail->discount ?? 0) }}">
                                            </td>
                                            <td>
                                                <span>{{ number_format(old("details.$index.tax", $detail->tax ?? 0), 2) }}</span>
                                                <input type="hidden" name="details[{{ $index }}][tax]"
                                                    value="{{ old("details.$index.tax", $detail->tax ?? 0) }}">
                                            </td>
                                            <td class="text-center" style="width:72px;">
                                                @if ($invoice->sales_order_id && $detail->sales_order_product_id && $detail->product_id && $warehouses->isNotEmpty())
                                                    <button type="button" class="btn btn-icon btn-sm bg-danger-subtle"
                                                        title="Remove line and return stock"
                                                        aria-label="Remove {{ $detail->product?->sku ?? 'product' }} from invoice and return stock"
                                                        data-bs-toggle="modal" data-bs-target="#returnInvoiceLineModal"
                                                        data-action="{{ route('invoice.details.return-stock', ['id' => $invoice->id, 'detailId' => $detail->id]) }}"
                                                        data-sku="{{ $detail->product?->sku ?? 'Product' }}"
                                                        data-quantity="{{ $detail->quantity }}"
                                                        data-last-line="{{ $invoice->details->count() === 1 ? 'true' : 'false' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-trash-2 text-danger" aria-hidden="true">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            {{-- <td>
                                                <span>{{ old("details.$index.description", $detail->tempOrder?->description ?? $detail->product?->brand_title ?? $detail->description) }}</span>
                                                <input type="hidden" name="details[{{ $index }}][description]"
                                                    value="{{ old("details.$index.description", $detail->tempOrder?->description ?? $detail->product?->brand_title ?? $detail->description) }}">
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" rows="3" name="notes">{{ old('notes', $invoice->notes) }}</textarea>
                        </div> --}}

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Invoice</button>
                            <a href="{{ route('invoices-details', $invoice->id) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                    <div class="modal fade" id="returnInvoiceLineModal" tabindex="-1"
                        aria-labelledby="returnInvoiceLineModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form id="returnInvoiceLineForm" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="returnInvoiceLineModalLabel">Return invoice item to stock</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Remove <strong id="returnInvoiceLineSku"></strong> (<span id="returnInvoiceLineQuantity"></span> units) from this invoice and add it to:</p>
                                        <label for="returnWarehouseId" class="form-label">Warehouse</label>
                                        <select name="warehouse_id" id="returnWarehouseId" class="form-select" required>
                                            <option value="">Select warehouse</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="returnInvoiceLineRemark" class="form-label mt-3">Remark</label>
                                        <textarea name="remark" id="returnInvoiceLineRemark" class="form-control" rows="2" maxlength="1000"></textarea>
                                        <div id="lastInvoiceLineNotice" class="alert alert-warning mt-3 mb-0 d-none">
                                            This is the final invoice item. Removing it will also remove the empty invoice.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Remove and return stock</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="quantityReturnModal" tabindex="-1"
                        aria-labelledby="quantityReturnModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="quantityReturnModalLabel">Return reduced quantity to stock</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p id="quantityReturnSummary" class="mb-3"></p>
                                    <label for="quantityReturnWarehouse" class="form-label">Warehouse</label>
                                    <select id="quantityReturnWarehouse" class="form-select" required>
                                        <option value="">Select warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="quantityReturnRemark" class="form-label mt-3">Remark</label>
                                    <textarea id="quantityReturnRemark" class="form-control" rows="2" maxlength="1000" placeholder="Optional"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" id="confirmQuantityReturn" class="btn btn-primary">Return and update invoice</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.getElementById('returnInvoiceLineModal')?.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            document.getElementById('returnInvoiceLineForm').action = trigger.dataset.action;
            document.getElementById('returnInvoiceLineSku').textContent = trigger.dataset.sku;
            document.getElementById('returnInvoiceLineQuantity').textContent = trigger.dataset.quantity;
            document.getElementById('lastInvoiceLineNotice').classList.toggle('d-none', trigger.dataset.lastLine !== 'true');
        });

        const invoiceUpdateForm = document.getElementById('invoiceUpdateForm');
        const quantityReturnModalElement = document.getElementById('quantityReturnModal');
        let confirmingQuantityReturn = false;

        invoiceUpdateForm?.addEventListener('submit', function (event) {
            if (confirmingQuantityReturn || !@json((bool) $invoice->sales_order_id)) {
                return;
            }

            const quantityInputs = Array.from(invoiceUpdateForm.querySelectorAll('[data-initial-quantity]'));
            const reductions = quantityInputs.reduce(function (total, input) {
                const difference = Number(input.dataset.initialQuantity) - Number(input.value);
                return total + Math.max(0, difference);
            }, 0);

            if (reductions <= 0) {
                return;
            }

            event.preventDefault();
            document.getElementById('quantityReturnSummary').textContent =
                `${reductions} unit(s) will be returned to the selected warehouse.`;
            bootstrap.Modal.getOrCreateInstance(quantityReturnModalElement).show();
        });

        document.getElementById('confirmQuantityReturn')?.addEventListener('click', function () {
            const warehouseSelect = document.getElementById('quantityReturnWarehouse');

            if (!warehouseSelect.value) {
                warehouseSelect.reportValidity();
                return;
            }

            document.getElementById('quantityReturnWarehouseId').value = warehouseSelect.value;
            document.getElementById('quantityReturnRemarkValue').value = document.getElementById('quantityReturnRemark').value;
            confirmingQuantityReturn = true;
            bootstrap.Modal.getOrCreateInstance(quantityReturnModalElement).hide();
            invoiceUpdateForm.requestSubmit();
        });
    </script>
@endsection
