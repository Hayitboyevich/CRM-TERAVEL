<?php

namespace Modules\QuiQoe\Services;

use App\Exceptions\CustomExceptionHandler;
use Illuminate\Pipeline\Pipeline;
use Modules\QuiQoe\DTO\SaveOrderPipelineDTO;
use Modules\QuiQoe\Services\Pipes\Flight;
use Modules\QuiQoe\Services\Pipes\Hotel;
use Modules\QuiQoe\Services\Pipes\Insurance;
use Modules\QuiQoe\Services\Pipes\SaveItems;
use Modules\QuiQoe\Services\Pipes\Transfers;
use Throwable;

class SaveOrderItemsPipeline
{
    public function pipes(Array $request){
        $orderItemsDto = new SaveOrderPipelineDTO($request);
        try {
            app(Pipeline::class)
                ->send($orderItemsDto)
                ->through([
                    Insurance::class,
                    Hotel::class,
                    Flight::class,
                    Transfers::class,
                    SaveItems::class,
                ])
                ->thenReturn();

        }catch (CustomExceptionHandler $exception){
            return $this->responseWithMessage($exception->getMessage(),'Error in processing',400);
        }
    }

    public function responseWithMessage($message, $status, $statusCode): \Illuminate\Http\JsonResponse
    {
        $responseData = [
            'status' => $status,
            'message' => $message,
        ];

        return response()->json($responseData, $statusCode);
    }
}
