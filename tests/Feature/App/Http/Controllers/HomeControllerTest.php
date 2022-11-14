<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\HomeController;
use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return  void
     */
    public function it_success_responce(): void
    {
        ProductFactory::new()
            ->onHomePage()
            ->sorting()
            ->count(5)->create();

        $product = ProductFactory::new()
            ->onHomePage()
            ->createOne([
                'sorting' => 1
            ]);

//        можно определять поля при создании не через метод  state в фабрике, а непосредственно в create
        CategoryFactory::new()
            ->count(5)->create([
                'on_home_page' => true,
                'sorting' => 999
            ]);

        $category = CategoryFactory::new()
            ->createOne([
                'on_home_page' => true,
                'sorting' => 1
            ]);

        BrandFactory::new()
            ->count(5)->create([
                'on_home_page' => true,
                'sorting' => 999
            ]);

        $brand = BrandFactory::new()
            ->createOne([
                'on_home_page' => true,
                'sorting' => 1
            ]);

        $this->get(action(HomeController::class))
            ->assertOk()
            ->assertViewHas('categories.0', $category)
            ->assertViewHas('brands.0', $brand)
            ->assertViewHas('products.0', $product);
    }
}
