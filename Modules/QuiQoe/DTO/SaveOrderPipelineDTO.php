<?php

namespace Modules\QuiQoe\DTO;

class SaveOrderPipelineDTO
{
    private $request;

    /**
     * @var OrderItemDTO[]
     */
    protected ?array $orderItems;
    private HotelsDTO $hotelsDto;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function setData(array $data): void
    {
        $this->orderItems[] = $data;
    }

    public function getOrderItems()
    {
        return $this->orderItems;
    }

    public function setHotelsDto(HotelsDTO $hotelsDto)
    {
        $this->hotelsDto = $hotelsDto;
    }

    public function getHotelsDto()
    {
        return $this->hotelsDto;
    }

    public function request()
    {
        return $this->request['request'];
    }

    public function order(){
        return $this->request['order'];
    }


}
