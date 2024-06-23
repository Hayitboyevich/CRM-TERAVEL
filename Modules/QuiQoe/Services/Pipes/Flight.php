<?php

namespace Modules\QuiQoe\Services\Pipes;

use App\Exceptions\CustomExceptionHandler;
use App\Models\Product;
use Carbon\Carbon;
use Closure;
use Modules\QuiQoe\DTO\HotelsDTO;
use Modules\QuiQoe\DTO\OrderItemDTO;
use Modules\QuiQoe\DTO\SaveOrderPipelineDTO;

class Flight
{
    public function handle(SaveOrderPipelineDTO $orderItemsDto, Closure $next)
    {
        $request = $orderItemsDto->request();
        $order = $orderItemsDto->order();
        $flights = $request->flights[0]->sectors;
        $product = Product::query()->where('name', 'LIKE', '%flights')->first();

        try {
            foreach ($flights as $flight) {
                $orderItemsDto->setData((new OrderItemDTO(
                    orderId: $order->id,
                    productId: $product?->id,
                    itemName: 'Авиабилет',
                    itemSummary: "Airline name: " . $flight->segments[0]->airlineName . '. Flight number: ' . $flight->segments[0]->flightNumber,
                    quantity: 0,
                    unitPrice: 0,
                    amount: 0,
                    adultsCount: null,
                    childrenCount: null,
                    infantCount: null,
                    dateFrom: !empty($flight->segments[0]->departureDate) ? Carbon::createFromFormat('d.m.Y', $flight->segments[0]->departureDate)->format('Y-m-d') : null,
                    dateTo: !empty($flight->segments[0]->arrivalDate) ? Carbon::createFromFormat('d.m.Y', $flight->segments[0]->arrivalDate)->format('Y-m-d') : null,
                    departureTime: $flight->segments[0]->departureTime,
                    arrivalTime: $flight->segments[0]->arrivalTime,
                    nights: null,
                    createdAt: now(),
                    updatedAt: now(),
                    currencyId: null,
                    countryId: null,
                ))->toArray());
            }
        } catch (CustomExceptionHandler $e) {
            throw new CustomExceptionHandler($e->getErrors());
        }

        return $next($orderItemsDto);
    }
}
