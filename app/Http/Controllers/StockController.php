<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvailableBalanceRequest;
use App\Services\StockService;

class StockController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Check if the requested quantity is in hand.
     * If so, get the service to calculate the cost of the required units
     *
     * @param AvailableBalanceRequest $request
     * @return array
     */
    public function checkAvailableQuantity(AvailableBalanceRequest $request)
    {
        $validated = $request->validated();

        $hasStock = $this->stockService->hasSufficientStock($validated['quantity']);
        if($hasStock['isAvailable'] === false){
            //Not enough stock. Return with available quantity for better UX
            return $hasStock;
        }

        return [
            'cost' => round($this->stockService->retrieveAverageCost($validated['quantity']),2) ,
            'totalAvailable' => $hasStock['totalAvailable'],
            'isAvailable' => true
        ];
    }
}
