<?php

namespace Modules\QuiQoe\Services\Pipes;

use App\Exceptions\CustomExceptionHandler;
use App\Models\Product;
use Carbon\Carbon;
use Closure;
use Modules\QuiQoe\DTO\HotelsDTO;
use Modules\QuiQoe\DTO\OrderItemDTO;
use Modules\QuiQoe\DTO\SaveOrderPipelineDTO;

class Transfers
{
    public function handle(SaveOrderPipelineDTO $orderItemsDto, Closure $next)
    {
        $request = $orderItemsDto->request();
        $order = $orderItemsDto->order();
        $transfers = $request->transfers;

        $product = Product::query()->where('name', 'LIKE', '%transfer')->first();

        try {
            foreach ($transfers as $transfer) {
                $orderItemsDto->setData((new OrderItemDTO(
                    orderId: $order->id,
                    productId: $product?->id,
                    itemName: 'transfers',
                    itemSummary: $transfer->description,
                    quantity: $transfer->count,
                    unitPrice: 0,
                    amount: 0,
                    adultsCount: $transfer->passengers->adults,
                    childrenCount: $transfer->passengers->children,
                    infantCount: $transfer->passengers->infants,
                    dateFrom: !empty($transfer->dateStart) ? Carbon::createFromFormat('d.m.Y', $transfer->dateStart)->format('Y-m-d') : null,
                    dateTo: null,
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
