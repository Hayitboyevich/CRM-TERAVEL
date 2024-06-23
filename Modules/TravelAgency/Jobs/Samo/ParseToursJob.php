<?php

namespace Modules\TravelAgency\Jobs\Samo;

use App\Models\HotelTour;
use App\Models\Test;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Tour\Models\Tour;

class ParseToursJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $fromWhere;
    private $toWhere;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $fromWhere, $toWhere)
    {
        $this->data = $data;
        $this->fromWhere = $fromWhere;
        $this->toWhere = $toWhere;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::query()->where('email', 'samo@mail.ru')->first();

        $tours = [];
        $tour_hotel = [];

        foreach ($this->data["SearchTour_PRICES"]['prices'] as $tour) {
            $end_date = new DateTime($tour['checkOut']);
            $last_booking_date = $end_date->modify('-1 day')->format('Y-m-d');
            $tours[] = [
                'start_date' => $tour['checkIn'],
                'end_date' => $tour['checkOut'],
                'last_booking_date' => $last_booking_date,
                'vendor_user_id' => $user->id,
                'vendor_tour_id' => $tour['tourKey'],
                'title' => $tour['tour'],
                'from_where' => $this->fromWhere->id,
                'to_where' => $this->toWhere->id,
                'location_id' => $this->toWhere->id,
                'enable_fixed_date' => 1,
                'vendor_hotel_id' => $tour['hotelKey'],
                'duration' => $tour['nights'] * 24,
                'status' => 'publish',
            ];
            $tour_hotel[] = [
                'vendor_tour_id' => $tour['tourKey'],
                'vendor_hotel_id' => $tour['hotelKey'],
                'vendor_user_id' => $user->id,
                'price' => $tour['convertedPriceNumber'],
                'start_date' => $tour['checkIn'],
                'end_date' => $tour['checkOut'],
                'currency' => $tour['convertedCurrency']
            ];
        }

        try {
            Tour::query()->upsert($tours, ['vendor_user_id', 'start_date', 'end_date', 'title'], ['location_id', 'from_where', 'to_where', 'vendor_tour_id', 'status', 'last_booking_date', 'enable_fixed_date', 'vendor_hotel_id', 'duration']);
            HotelTour::query()->upsert($tour_hotel, ['hotel_tour_unique'], ['price', 'currency']);
        } catch (Exception $exception) {
            Log::error('Error in parsing tours', ['sms' => $exception->getMessage()]);
        }
    }
}
