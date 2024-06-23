<?php

namespace Modules\QuiQoe\Services\Pipes;

use App\Exceptions\CustomExceptionHandler;
use App\Models\Product;
use Carbon\Carbon;
use Closure;
use Modules\QuiQoe\DTO\OrderItemDTO;
use Modules\QuiQoe\DTO\SaveOrderPipelineDTO;

class Insurance
{
    public function handle(SaveOrderPipelineDTO $orderItemsDto, Closure $next)
    {
        $request = $orderItemsDto->request();
        $order = $orderItemsDto->order();
        $insurances = $request->insurance;

        $product = Product::query()->where('name', 'LIKE', '%insurance')->first();
        try {
            foreach ($insurances as $insurance) {
                $orderItemsDto->setData((new OrderItemDTO(
                    orderId: $order->id,
                    productId: $product?->id,
                    itemName: 'Страхование',
                    itemSummary: $insurance->description,
                    quantity: $insurance->count,
                    unitPrice: 0,
                    amount: 0,
                    adultsCount: $insurance->passengers->adults,
                    childrenCount: $insurance->passengers->children,
                    infantCount: $insurance->passengers->infants,
                    dateFrom: !empty($insurance->dateStart) ? Carbon::createFromFormat('d.m.Y', $insurance->dateStart)->format('Y-m-d') : null,
                    dateTo: !empty($insurance->dateEnd) ? Carbon::createFromFormat('d.m.Y', $insurance->dateEnd)->format('Y-m-d') : null,
                    departureTime: null,
                    arrivalTime: null,
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
