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

                    <form action="{{ route('invoice.update', $invoice->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Invoice Date</label>
                                <input type="date" class="form-control" name="invoice_date"
                                    value="{{ old('invoice_date', optional($invoice->invoice_date)->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">PO Number</label>
                                <input type="text" class="form-control" name="po_number"
                                    value="{{ old('po_number', $invoice->po_number) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">PO Date</label>
                                <input type="date" class="form-control" name="po_date"
                                    value="{{ old('po_date', optional($invoice->po_date)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Round Off</label>
                                <input type="number" step="0.01" class="form-control" name="round_off"
                                    value="{{ old('round_off', $invoice->round_off ?? 0) }}">
                            </div>
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
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->details as $index => $detail)
                                        <tr>
                                            <td>
                                                {{ $detail->product->product_name ?? $detail->service_title ?? 'N/A' }}<br>
                                                <small class="text-muted">{{ $detail->product->sku ?? $detail->item_code ?? '-' }}</small>
                                                <input type="hidden" name="details[{{ $index }}][id]"
                                                    value="{{ $detail->id }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="details[{{ $index }}][hsn]"
                                                    value="{{ old("details.$index.hsn", $detail->hsn) }}">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0.01" class="form-control"
                                                    name="details[{{ $index }}][quantity]"
                                                    value="{{ old("details.$index.quantity", $detail->quantity) }}" required>
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
                                                <input type="number" step="0.01" min="0" class="form-control"
                                                    name="details[{{ $index }}][unit_price]"
                                                    value="{{ old("details.$index.unit_price", $detail->unit_price) }}" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0" class="form-control"
                                                    name="details[{{ $index }}][discount]"
                                                    value="{{ old("details.$index.discount", $detail->discount ?? 0) }}">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0" class="form-control"
                                                    name="details[{{ $index }}][tax]"
                                                    value="{{ old("details.$index.tax", $detail->tax ?? 0) }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="details[{{ $index }}][description]"
                                                    value="{{ old("details.$index.description", $detail->description) }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" rows="3" name="notes">{{ old('notes', $invoice->notes) }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Invoice</button>
                            <a href="{{ route('invoices-details', $invoice->id) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
