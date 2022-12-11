<?php

namespace Database\Seeders;

use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
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
            ],
            [
                'movementDate' => Carbon::parse('2020-06-23'),
                'type' => 'Application',
                'quantity' => -37,
                'unitPrice' => null
            ],
            [
                'movementDate' => Carbon::parse('2020-07-10'),
                'type' => 'Purchase',
                'quantity' => 47,
                'unitPrice' => 4.3
            ],
            [
                'movementDate' => Carbon::parse('2020-07-12'),
                'type' => 'Application',
                'quantity' => -38,
                'unitPrice' => null
            ],
            [
                'movementDate' => Carbon::parse('2020-07-13'),
                'type' => 'Purchase',
                'quantity' => 10,
                'unitPrice' => 5
            ],
            [
                'movementDate' => Carbon::parse('2020-07-25'),
                'type' => 'Purchase',
                'quantity' => 50,
                'unitPrice' => 4.2
            ],
            [
                'movementDate' => Carbon::parse('2020-07-25'),
                'type' => 'Application',
                'quantity' => -28,
                'unitPrice' => null
            ],
            [
                'movementDate' => Carbon::parse('2020-07-31'),
                'type' => 'Purchase',
                'quantity' => 10,
                'unitPrice' => 5
            ],
            [
                'movementDate' => Carbon::parse('2020-08-14'),
                'type' => 'Purchase',
                'quantity' => 15,
                'unitPrice' => 5
            ],
            [
                'movementDate' => Carbon::parse('2020-08-17'),
                'type' => 'Purchase',
                'quantity' => 3,
                'unitPrice' => 6
            ],
            [
                'movementDate' => Carbon::parse('2020-08-29'),
                'type' => 'Purchase',
                'quantity' => 2,
                'unitPrice' => 7
            ],
            [
                'movementDate' => Carbon::parse('2020-08-31'),
                'type' => 'Application',
                'quantity' => -30,
                'unitPrice' => null
            ],
        ];
    }
}
