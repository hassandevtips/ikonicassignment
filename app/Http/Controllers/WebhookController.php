<?php

namespace App\Http\Controllers;

use App\Services\AffiliateService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * Pass the necessary data to the process order method
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: Complete this method
        $data = $request->all();

        // 2. Process the Order
        try {
            $this->orderService->processOrder($data); // Pass data to the OrderService method
            return response()->json(['message' => 'Order processed successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
