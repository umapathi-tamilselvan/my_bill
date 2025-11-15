@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('header-actions')
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Customer
    </a>
@endsection

@section('content')
    <x-table :headers="['Code', 'Name', 'Email', 'Phone', 'Address', 'Balance', 'Status', 'Actions']">
        @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->code }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email ?? 'N/A' }}</td>
                <td>{{ $customer->phone ?? 'N/A' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($customer->address ?? 'N/A', 30) }}</td>
                <td>{{ number_format($customer->opening_balance, 2) }}</td>
                <td>
                    <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $customer->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('customers.ledger', $customer->id) }}" class="btn btn-sm btn-info" title="Ledger">
                        <i class="bi bi-journal-text"></i>
                    </a>
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline">
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
                <td colspan="8" class="text-center">No customers found.</td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-3">
        {{ $customers->links() }}
    </div>
@endsection

