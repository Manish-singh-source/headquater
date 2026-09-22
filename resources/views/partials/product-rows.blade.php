@forelse ($products as $product)
    @php($productData = $product->productData)
    @if ($productData?->id)
        <tr>
            <td>
                <input class="form-check-input row-checkbox" type="checkbox" name="ids[]"
                    value="{{ $productData?->id }}">
            </td>
            <td>{{ $product->warehouse->name ?? 'NA' }}</td>
            <td>{{ $productData?->sku ?? 'NA' }}</td>
            <td>{{ $productData?->ean_code ?? 'NA' }}</td>
            <td>
                <div class="d-flex align-items-center gap-3">
                    <div class="product-info">
                        <a href="javascript:;" class="product-title">{{ $productData?->brand ?? 'NA' }}</a>
                    </div>
                </div>
            </td>
            <td>{{ $productData?->brand_title ?? 'NA' }}</td>
            <td>{{ $productData?->category ?? 'NA' }}</td>
            <td>{{ $productData?->pcs_set ?? 0 }}</td>
            <td>{{ $productData?->sets_ctn ?? 0 }}</td>
            <td>{{ $productData?->weight ?? 0 }}</td>
            <td>{{ $productData?->case_pack_quantity ?? 0 }}</td>
            <td>{{ $productData?->vendor_code ?? 'NA' }}</td>
            <td>{{ $productData?->vendor_name ?? 'NA' }}</td>
            <td>{{ $productData?->vendor_purchase_rate ?? 0 }}</td>
            <td>{{ $productData?->gst ?? 0 }}</td>
            <td>{{ $productData?->hsn ?? 'NA' }}</td>
            <td>{{ $productData?->vendor_net_landing ?? 0 }}</td>
            <td>{{ $productData?->status == '1' ? 'Active' : 'Inactive' }}</td>
            <td>{{ $product->original_quantity ?? 0 }}</td>
            <td>{{ $product->available_quantity ?? 0 }}</td>
            <td>
                @if ($product->block_quantity)
                    <span class="badge text-danger bg-danger-subtle">{{ $product->block_quantity }}</span>
                @else
                    <span>0</span>
                @endif
            </td>
            <td>
                @if ($product->allocated_quantity ?? 0)
                    <span class="badge text-warning bg-warning-subtle">{{ $product->allocated_quantity }}</span>
                @else
                    <span>0</span>
                @endif
            </td>
            <td>
                @if ($product->po_required ?? 0)
                    <span class="badge text-info bg-info-subtle">{{ $product->po_required }}</span>
                @else
                    <span>0</span>
                @endif
            </td>
            <td>{{ $productData?->created_at?->format('d-M-Y') }}</td>
            <td>
                <div class="d-flex">
                    <a aria-label="anchor" data-id="{{ $productData?->id }}" href="javascript:void(0);"
                        class="btn btn-icon btn-sm bg-warning-subtle me-1 editProductBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-edit text-warning">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </a>
                    <form action="{{ route('product.delete', $productData?->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-icon btn-sm bg-danger-subtle delete-row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @endif
@empty
    <tr>
        <td colspan="25" class="text-center">No products found.</td>
    </tr>
@endforelse
