<?php

namespace Modules\QuiQoe\DTO;

use App\Http\Requests\Attendance\StoreAttendance;

class OrderItemDTO
{
    /**
     * @param int $orderId
     * @param int|null $productId
     * @param string|null $itemName
     * @param string|null $itemSummary
     * @param int|null $quantity
     * @param int|null $unitPrice
     * @param int|null $amount
     * @param int|null $adultsCount
     * @param int|null $childrenCount
     * @param int|null $infantCount
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $departureTime
     * @param string|null $arrivalTime
     * @param string|null $createdAt
     * @param string|null $updatedAt
     * @param int|null $nights
     * @param int|null $currencyId
     * @param int|null $countryId
     */

    public function __construct(
        public readonly int  $orderId,
        public readonly ?int $productId,
        public readonly ?string $itemName,
        public readonly ?string $itemSummary,
        public readonly ?int $quantity,
        public readonly ?int $unitPrice,
        public readonly ?int $amount,
        public readonly ?int $adultsCount,
        public readonly ?int $childrenCount,
        public readonly ?int $infantCount,
        public readonly ?string $dateFrom,
        public readonly ?string $dateTo,
        public readonly ?string $departureTime,
        public readonly ?string $arrivalTime,
        public readonly ?int $nights,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
        public readonly ?int $currencyId,
        public readonly ?int $countryId,

    ){}

    public function toArray(): array
    {
        return [
            'order_id' => $this->orderId,
            'product_id' => $this->productId,
            'item_name' => $this->itemName,
            'item_summary' => $this->itemSummary,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'amount' => $this->amount,
            'adults_count' => $this->adultsCount,
            'children_count' => $this->childrenCount,
            'infants_count' => $this->infantCount,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'departure_time' => $this->departureTime,
            'arrival_time' => $this->arrivalTime,
            'nights' => $this->nights,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'currency_id' => $this->currencyId,
            'country_id' => $this->countryId,
        ];
    }
}
