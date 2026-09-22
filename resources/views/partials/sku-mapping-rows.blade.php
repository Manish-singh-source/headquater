@forelse($productMapping as $product)
    <tr>
        <td>
            <input class="form-check-input row-checkbox" type="checkbox" name="ids[]" value="{{ $product->id }}">
        </td>
        <td>{{ $product->sku }}</td>
        <td>{{ $product->portal_code }}</td>
        <td>{{ $product->item_code }}</td>
        <td>{{ $product->basic_rate ?? 0 }}</td>
        <td>{{ $product->net_landing_rate ?? 0 }}</td>
        <td>{{ $product->mrp ?? 0 }}</td>
        <td>
            <div class="d-flex">
                <a aria-label="anchor" href="{{ route('sku.mapping.edit', $product->id) }}"
                    class="btn btn-icon btn-sm bg-warning-subtle me-1" data-bs-toggle="tooltip"
                    data-bs-original-title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-edit text-warning">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </a>
                <form action="{{ route('sku.mapping.destroy', $product->id) }}" method="POST"
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
@empty
    <tr>
        <td class="text-center" colspan="8">None SKU Mapping Found</td>
    </tr>
@endforelse
