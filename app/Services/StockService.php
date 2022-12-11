<?php

namespace App\Services;

use App\Models\StockMovement;

class StockService
{

    const CHUNK = 2;

    public $inHand = null;
    public $records = [];

    /**
     * Handler method to retrieve the average cost of the stock in hand
     * @param int $quantity
     * @return float|int
     */
    public function retrieveAverageCost(int $quantity = 0)
    {
        if($this->inHand === null){
            $this->hasSufficientStock($quantity);
        }

        $this->retrieveRecords();

        return $this->calculateCost();
    }

    /**
     * Calculate the cost of the stock in hand
     * @return float|int
     */
    public function calculateCost(): float
    {
        if(count($this->records) == 0){ return 0; }

        //Seperate the oldest purchase since not all units from this purchase will be applicable to the cost
        $oldestPurchase = array_pop($this->records);
        $quantityForOldestPurchase = $this->identifyQuantityLeftFromFirstPurchase();

        $cost = 0;

        //Using foreach since it's faster than array_map and is easier to read
        //Calculate the cost for balance purchases except the oldest
        foreach($this->records as $record){
            $cost += ($record['quantity'] * $record['unitPrice']);
        }

        //Add the value of stock that can be assigned to the oldest purchase
        $cost += ($oldestPurchase['unitPrice'] * $quantityForOldestPurchase);

        return $cost;
    }

    /**
     * Calculate the quantity left after previous purchases which is needed to assign to the oldest purchase
     * @return float|int|null
     */
    public function identifyQuantityLeftFromFirstPurchase(): int
    {
        return $this->inHand - array_sum(array_column($this->records, 'quantity'));
    }

    /**
     * Retrieve records from DB upto where the running total matches stock in hand
     * In other words identify index 0 for the records that have the current stock in hand
     */
    public function retrieveRecords(): void
    {
        $runningTotal = 0;
        $movementData = [];
        StockMovement::select('id','movementDate','type','quantity','unitPrice')->where('type','purchase')->orderBy('id','DESC')
                        ->chunk(self::CHUNK, function($records) use(&$runningTotal, &$movementData){
                            foreach($records->toArray() as $record){
                                $runningTotal += $record['quantity'];
                                $movementData[] = $record;
                                if($runningTotal >= $this->inHand){
                                    return false;
                                }
                            }
                        });
        $this->records = $movementData;
    }


    /**
     * Check if there is sufficient stock for a given amount
     * @param integer $quantity Quantity to check against
     */
    public function hasSufficientStock(int $quantity = 0): array
    {
        $available = $this->getAvailableBalance();
        $this->inHand = $available;
        return [
                    'isAvailable' => $available >= $quantity,
                    'totalAvailable' => $available
               ];
    }

    /**
     * Getting available balance to display the available amount in the error message
     * If displaying the amount is not required, we can simply return a bool after checking if available or not
     */
    public function getAvailableBalance(): int
    {
        return StockMovement::sum('quantity');
    }
}
