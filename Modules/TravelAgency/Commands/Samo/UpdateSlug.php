<?php

namespace Modules\TravelAgency\Commands\Samo;

use App\Models\HotelStar;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Modules\Hotel\Models\Hotel;
use Modules\Location\Models\Location;
use Modules\Tour\Models\Tour;

class UpdateSlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:slug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all hotels
        $user = User::query()->where('email', 'samo@mail.ru')->first();
        $locations = Location::query()->where('vendor_user_id', $user->id)->get();
        foreach ($locations as $location) {
            $location->slug = Str::slug($location->name);
            $location->save();
            $this->info('Location slug saved' . $location->slug);
        }

        $this->info('Location slugs updated successfully.');
    }
}
