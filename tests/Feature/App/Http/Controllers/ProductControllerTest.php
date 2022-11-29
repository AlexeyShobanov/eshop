<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\ProductController;
use Database\Factories\PropertyFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return  void
     */
    public function it_success_response(): void
    {
        $product = PropertyFactory::new()->createOne();

        $this->get(action(ProductController::class, $product))
            ->assertOk();
    }
}
