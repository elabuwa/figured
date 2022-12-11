<?php

namespace Tests\Feature;

use Database\Seeders\StockMovementSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StockAvailabilityTest extends TestCase
{
    /**
     * Test form validation
     *
     * @return void
     */
    public function test_validation()
    {
        $this->seed(StockMovementSeeder::class);

        $response = $this->post('/api/checkAvailableQuantity',
            [],
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['quantity']);
    }

    /**
     * Test stock level response
     *
     * @return void
     */
    public function test_stock_availability()
    {
        $this->seed(StockMovementSeeder::class);

        $response = $this->post('/api/checkAvailableQuantity',
            ['quantity' => 10],
            [
                'Accept' => 'application/json',
            ]
        );

        $response->assertOk();
        $this->assertJson(json_encode(['cost' => 211.6, 'totalAvailable' => 43]));

        $response = $this->post('/api/checkAvailableQuantity',
            ['quantity' => 99],
            [
                'Accept' => 'application/json',
            ]
        );
        $response->assertOk();
        $this->assertJson(json_encode(['isAvailable' => false, 'totalAvailable' => 43]));
    }
}
