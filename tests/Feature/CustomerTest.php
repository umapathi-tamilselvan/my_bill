<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_customer(): void
    {
        $customerData = [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'address' => '123 Test Street',
            'is_active' => true,
        ];

        $response = $this->post(route('customers.store'), $customerData);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
        ]);
    }

    public function test_can_view_customer_ledger(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.ledger', $customer->id));

        $response->assertStatus(200);
        $response->assertViewIs('customers.ledger');
    }
}

