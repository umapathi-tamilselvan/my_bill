@extends('layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')

@section('header-actions')
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Product
    </a>
@endsection

@section('content')
    <x-table :headers="['Code', 'Name', 'Price', 'Stock', 'Unit', 'Tax Rate', 'Status', 'Actions']">
        @forelse($products as $product)
            <tr>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>
                    <span class="badge {{ $product->stock < 10 ? 'bg-danger' : 'bg-success' }}">
                        {{ $product->stock }}
                    </span>
                </td>
                <td>{{ $product->unit }}</td>
                <td>{{ $product->tax_rate }}%</td>
                <td>
                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No products found.</td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
@endsection

