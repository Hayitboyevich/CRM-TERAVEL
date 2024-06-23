<?php

namespace Modules\QuiQoe\Services\Pipes;

use App\Exceptions\CustomExceptionHandler;
use App\Models\OrderItems;
use Closure;
use Modules\QuiQoe\DTO\HotelsDTO;
use Modules\QuiQoe\DTO\SaveOrderPipelineDTO;

class SaveItems
{
    public function handle(SaveOrderPipelineDTO $orderItemsDto, Closure $next)
    {
//        $orderItems = [];
//        foreach ($orderItemsDto->getOrderItems() as $orderItem) {
//            $orderItems[] = $orderItem->toArray();
//        }
        try {
            \App\Models\Hotel::query()->insert($orderItemsDto->getHotelsDto()->getHotels());
            OrderItems::query()->insert($orderItemsDto->getOrderItems());

        } catch (CustomExceptionHandler $e) {
            throw new CustomExceptionHandler($e->getErrors());
        }

        return $next($orderItemsDto);
    }
}
