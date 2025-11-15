@extends('layouts.app')

@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Customer Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <x-input name="code" label="Customer Code" value="{{ old('code', $customer->code) }}" readonly />
                        <x-input name="name" label="Customer Name" value="{{ old('name', $customer->name) }}" required />
                        <x-input name="email" label="Email" type="email" value="{{ old('email', $customer->email) }}" />
                        <x-input name="phone" label="Phone" value="{{ old('phone', $customer->phone) }}" />
                        <x-input name="address" label="Address" type="textarea" value="{{ old('address', $customer->address) }}" />
                        <div class="row">
                            <div class="col-md-4">
                                <x-input name="city" label="City" value="{{ old('city', $customer->city) }}" />
                            </div>
                            <div class="col-md-4">
                                <x-input name="state" label="State" value="{{ old('state', $customer->state) }}" />
                            </div>
                            <div class="col-md-4">
                                <x-input name="zip_code" label="Zip Code" value="{{ old('zip_code', $customer->zipCode) }}" />
                            </div>
                        </div>
                        <x-input name="opening_balance" label="Opening Balance" type="number" step="0.01" value="{{ old('opening_balance', $customer->openingBalance) }}" />
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $customer->isActive) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Customer</button>
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

