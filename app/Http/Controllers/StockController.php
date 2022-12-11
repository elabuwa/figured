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

    public function checkAvailableQuantity(AvailableBalanceRequest $request)
    {
        $validated = $request->validated();

        $hasStock = $this->stockService->hasSufficientStock($validated['quantity']);
        if($hasStock['isAvailable'] === false){
            //Not enough stock. Return with available quantity for better UX
            return $hasStock;
        }

        return ['cost' => $this->stockService->retrieveAverageCost($validated['quantity']) ];
    }
}
