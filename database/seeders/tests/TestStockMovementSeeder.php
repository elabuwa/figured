<?php

namespace Database\Seeders\Tests;

use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestStockMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedData = $this->getSeedData();
        foreach ($seedData as $seed){
            StockMovement::create($seed);
        }
    }

    private function getSeedData()
    {
        return [
            [
                'movementDate' => Carbon::parse('2020-06-05'),
                'type' => 'Purchase',
                'quantity' => 10,
                'unitPrice' => 5
            ],
            [
                'movementDate' => Carbon::parse('2020-06-07'),
                'type' => 'Purchase',
                'quantity' => 30,
                'unitPrice' => 4.5
            ],
            [
                'movementDate' => Carbon::parse('2020-06-08'),
                'type' => 'Application',
                'quantity' => -20,
                'unitPrice' => null
            ],
            [
                'movementDate' => Carbon::parse('2020-06-09'),
                'type' => 'Purchase',
                'quantity' => 10,
                'unitPrice' => 5
            ],
            [
                'movementDate' => Carbon::parse('2020-06-10'),
                'type' => 'Purchase',
                'quantity' => 34,
                'unitPrice' => 4.5
            ],
            [
                'movementDate' => Carbon::parse('2020-06-15'),
                'type' => 'Application',
                'quantity' => -25,
                'unitPrice' => null
            ]
        ];
    }
}
