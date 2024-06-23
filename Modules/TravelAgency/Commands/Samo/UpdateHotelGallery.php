<?php

namespace Modules\TravelAgency\Commands\Samo;

use App\Models\User;
use Illuminate\Console\Command;
use Modules\Hotel\Models\Hotel;
use Modules\Media\Models\MediaFile;

class UpdateHotelGallery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:gallery';

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

        $hotels = Hotel::query()->where('vendor_user_id', $user->id)->get();

        foreach ($hotels as $hotel) {
            // Get media file with matching namefailed_jobs
            $mediaFile = MediaFile::where('file_name', $hotel->title)->get();
            $ids = [];
            foreach ($mediaFile as $item) {
                $ids[] = $item->id;
            }
            $id = reset($ids);
            // If there is a match, update hotel's gallery with media file ID
            $photo_ids = implode(',', $ids);
            if ($mediaFile) {
                $hotel->image_id = $id;
                $hotel->banner_image_id = $id;
                $hotel->gallery = $photo_ids;
                $hotel->save();
                $this->info("Updated hotel #{$hotel->id} with media file #{$photo_ids}");
            }
        }

        $this->info('Hotel galleries updated successfully.');
    }
}
