<?php

declare(strict_types=1);

namespace Tests\Feature\App\Jobs;

use App\Jobs\ProductJsonProperties;
use Database\Factories\ProductFactory;
use Database\Factories\PropertyFactory;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

final class ProductJsonPropertiesTest extends TestCase
{
    /**
     * @test
     * @return  void
     */
    public function it_created_json_properties(): void
    {
        // получаем инстанс очереди
        $queue = Queue::getFacadeRoot();

        // создаем файк очереди
        Queue::fake([ProductJsonProperties::class]);

        // создаем набор свойств
        $properties = PropertyFactory::new()
            ->count(10)
            ->create();

        // создаем товар, с атачем свойств, в колбеке переопределяем для каждого свойства новое значение value (иначе у всех одно и тоже)
        $product = ProductFactory::new()
            ->hasAttached($properties, function () {
                return ['value' => fake()->word()];
            })
            ->create();

        // проверяем что $product->json_properties пустое
        $this->assertEmpty($product->json_properties);

        //создаем реальную очередь
        Queue::swap($queue);

        //запускаем job в синхронном режиме
        ProductJsonProperties::dispatchSync($product);

        // обновляем все связи для продукта
        $product->refresh();

        // проверяем что $product->json_properties не пустое
        $this->assertNotEmpty($product->json_properties);
    }
}