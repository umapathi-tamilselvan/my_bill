@extends('layouts.app')

@section('title', 'Add Customer')
@section('page-title', 'Add Customer')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Customer Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf
                        
                        <x-input name="code" label="Customer Code" value="{{ old('code') }}" />
                        <x-input name="name" label="Customer Name" value="{{ old('name') }}" required />
                        <x-input name="email" label="Email" type="email" value="{{ old('email') }}" />
                        <x-input name="phone" label="Phone" value="{{ old('phone') }}" />
                        <x-input name="address" label="Address" type="textarea" value="{{ old('address') }}" />
                        <div class="row">
                            <div class="col-md-4">
                                <x-input name="city" label="City" value="{{ old('city') }}" />
                            </div>
                            <div class="col-md-4">
                                <x-input name="state" label="State" value="{{ old('state') }}" />
                            </div>
                            <div class="col-md-4">
                                <x-input name="zip_code" label="Zip Code" value="{{ old('zip_code') }}" />
                            </div>
                        </div>
                        <x-input name="opening_balance" label="Opening Balance" type="number" step="0.01" value="{{ old('opening_balance', 0) }}" />
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Customer</button>
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

