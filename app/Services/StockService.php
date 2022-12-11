<?php

namespace App\Services;

use App\Models\StockMovement;

class StockService
{

    const CHUNK = 2;

    public int|null $inHand = null;
    public array|null $records = null;

    /**
     * Handler method to retrieve the average cost of the stock in hand
     * @return float|int
     */
    public function retrieveAverageCost()
    {
        if($this->inHand === null){
            $this->getAvailableBalance();
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
        if(is_array($this->records) === false){ $this->retrieveRecords(); }
        if(count($this->records) === 0){ return 0; }

        //Separate the oldest purchase since not all units from this purchase will be applicable to the cost
        $oldestPurchase = end($this->records);
        $quantityForOldestPurchase = $this->identifyQuantityLeftFromFirstPurchase();

        $cost = 0;

        //Using foreach since it's faster than array_map and is easier to read
        //Calculate the cost for balance purchases except the oldest
        $count = count($this->records);
        foreach($this->records as $key=>$val){
            if (--$count <= 0) {
                break;
            }
            $cost += ($val['quantity'] * $val['unitPrice']);
        }

        //Add the value of stock that can be assigned to the oldest purchase
        $cost += ($oldestPurchase['unitPrice'] * $quantityForOldestPurchase);

        return $cost;
    }

    /**
     * Calculate the quantity left after previous purchases which is needed to assign to the oldest purchase
     * @return int
     */
    public function identifyQuantityLeftFromFirstPurchase(): int
    {
        if($this->inHand == null){ $this->getAvailableBalance(); }
        if($this->records == null){ $this->retrieveRecords(); }

        $last = end($this->records);

        return abs($this->inHand - (array_sum(array_column($this->records, 'quantity')) - $last['quantity']));
    }

    /**
     * Retrieve records from DB upto where the running total matches stock in hand
     * In other words identify index 0 for the records that have the current stock in hand
     */
    public function retrieveRecords(): void
    {
        if($this->inHand === null){ $this->getAvailableBalance(); }
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
        if($this->inHand === null){
            $this->getAvailableBalance();
        }
        return [
                    'isAvailable' => $this->inHand >= $quantity,
                    'totalAvailable' => $this->inHand
               ];
    }

    /**
     * Getting available balance to display the available amount in the error message
     * If displaying the amount is not required, we can simply return a bool after checking if available or not
     */
    public function getAvailableBalance(): int
    {
        $balance = StockMovement::sum('quantity');
        $this->inHand = $balance;
        return $balance;
    }
}
