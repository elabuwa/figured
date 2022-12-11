<?php

namespace Tests\Unit;

use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Database\Seeders\Tests\TestStockMovementSeeder;
use Database\Seeders\StockMovementSeeder;

class StockServiceTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test the stock in hand
     *
     * @return void
     */

    public function test_stockInHandQty()
    {
        $this->seed(TestStockMovementSeeder::class);

        $service = new StockService();
        $this->assertEquals(39, $service->getAvailableBalance());

        StockMovement::query()->delete();

        $this->seed(StockMovementSeeder::class);

        $service = new StockService();
        $this->assertEquals(43, $service->getAvailableBalance());
    }

    public function test_sufficientStockCheck()
    {
        $this->seed(StockMovementSeeder::class);

        $service = new StockService();
        $result = $service->hasSufficientStock(10);
        $this->assertEquals(true, $result['isAvailable']);

        $result = $service->hasSufficientStock(100);
        $this->assertEquals(false, $result['isAvailable']);

        $result = $service->hasSufficientStock(43);
        $this->assertEquals(true, $result['isAvailable']);

        $result = $service->hasSufficientStock(44);
        $this->assertEquals(false, $result['isAvailable']);
    }

    public function test_identifyQuantityLeftFromFirstPurchase(){

        $this->seed(TestStockMovementSeeder::class);

        $service = new StockService();
        $service->hasSufficientStock(10);
        $service->retrieveRecords();
        $this->assertSame(5, $service->identifyQuantityLeftFromFirstPurchase());

        StockMovement::query()->delete();

        $this->seed(StockMovementSeeder::class);

        $service = new StockService();
        $service->hasSufficientStock(10);
        $service->retrieveRecords();
        $this->assertSame(13, $service->identifyQuantityLeftFromFirstPurchase());
    }

    public function test_recordRetrieval()
    {
        $this->seed(TestStockMovementSeeder::class);

        $service = new StockService();
        $service->retrieveRecords();
        $this->assertSame(2, count($service->records));

        StockMovement::query()->delete();

        $this->seed(StockMovementSeeder::class);
        $service = new StockService();
        $service->retrieveRecords();
        $this->assertSame(5, count($service->records));
    }

    public function test_stockCost()
    {
        $this->seed(TestStockMovementSeeder::class);

        $service = new StockService();
        $this->assertSame(178.0, $service->calculateCost());

        StockMovement::query()->delete();

        $this->seed(StockMovementSeeder::class);
        $service = new StockService();
        $this->assertSame(211.6, $service->calculateCost());
    }
}
