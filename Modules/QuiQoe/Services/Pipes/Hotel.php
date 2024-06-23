<?php

namespace Modules\QuiQoe\Services\Pipes;

use App\Exceptions\CustomExceptionHandler;
use App\Models\Product;
use Carbon\Carbon;
use Closure;
use Modules\QuiQoe\DTO\HotelDTO;
use Modules\QuiQoe\DTO\HotelsDTO;
use Modules\QuiQoe\DTO\OrderItemDTO;
use Modules\QuiQoe\DTO\SaveOrderPipelineDTO;

class Hotel
{
    public function handle(SaveOrderPipelineDTO $orderItemsDto, Closure $next)
    {
        $request = $orderItemsDto->request();
        $order = $orderItemsDto->order();
        $hotels = $request->hotels;
        $product = Product::query()->where('name', 'LIKE', '%hotel')->first();
        $orderItemsDto->setHotelsDto(new HotelsDTO());
        $hotelsDto = $orderItemsDto->getHotelsDto();
        try {
            foreach ($hotels as $hotel) {
                $hotelsDto->setData((new HotelDTO(null, null, $hotel?->hotelName, now(), now()))->toArray());
                $orderItemsDto->setData(
                    (new OrderItemDTO(
                        orderId: $order->id,
                        productId: $product?->id,
                        itemName: 'Отель',
                        itemSummary: "Hotel name: " . $hotel?->hotelName . ". Room type: " . $hotel?->roomType . ". Nights: " . $hotel?->nights,
                        quantity: $hotel->count,
                        unitPrice: 0,
                        amount: 0,
                        adultsCount: $hotel->passengers->adults,
                        childrenCount: $hotel->passengers->children,
                        infantCount: $hotel->passengers->infants,
                        dateFrom: !empty($hotel->dateStart) ? Carbon::createFromFormat('d.m.Y', $hotel->dateStart)->format('Y-m-d') : null,
                        dateTo: !empty($hotel->dateStart) ? Carbon::createFromFormat('d.m.Y', $hotel->dateStart)->format('Y-m-d') : null,
                        departureTime: null,
                        arrivalTime: null,
                        nights: $hotel->nights,
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
