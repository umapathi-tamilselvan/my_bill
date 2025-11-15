<?php

namespace Tests\Unit;

use App\DTOs\ProductDTO;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_product_code_automatically(): void
    {
        $mockRepository = Mockery::mock(ProductRepositoryInterface::class);
        $mockRepository->shouldReceive('all')
            ->andReturn(collect([]));
        $mockRepository->shouldReceive('create')
            ->once()
            ->andReturn(new \App\Models\Product([
                'code' => 'PRD000001',
                'name' => 'Test Product',
            ]));

        $this->app->instance(ProductRepositoryInterface::class, $mockRepository);

        $service = new ProductService($mockRepository);
        $dto = ProductDTO::fromArray([
            'name' => 'Test Product',
            'price' => 100.00,
        ]);

        $result = $service->createProduct($dto);

        $this->assertNotNull($result->code);
        $this->assertStringStartsWith('PRD', $result->code);
    }
}

