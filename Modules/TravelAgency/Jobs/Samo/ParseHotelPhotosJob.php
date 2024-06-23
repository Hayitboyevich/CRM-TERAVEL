<?php

namespace Modules\TravelAgency\Jobs\Samo;

use App\Models\HotelsUrl;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Media\Models\MediaFile;
use Modules\TravelAgency\Client\Curl;
use Modules\TravelAgency\DOM\HtmlDomAdapter;

class ParseHotelPhotosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $selector;
    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->selector = '.td.left > div > div > a';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::query()->where('email', 'samo@mail.ru')->first();
        $client = (new Curl())->init($this->data)->execute();
        $dom = new HtmlDomAdapter($client->getBody());

        // Get the hotel name
        $hotelName = preg_replace('/\s*\d+\*/', '', $dom->find('.td.left h2')->text());

        // Create a directory to store the photos, if it doesn't exist
        $directory = public_path('uploads/demo/hotel/gallery/');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Save the photos to the directory and store the file paths in the media_files table
        $photos = $dom->find('.sp-thumbnail')->each(function ($node) use ($directory, $hotelName, $user) {
            $filename = basename($node->attr('src'));
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $path = $directory . '/' . $filename;
            file_put_contents($path, file_get_contents('https://kompastour.com' . $node->attr('src')));
            return [
                'file_path' => 'demo/hotel/gallery/' . $filename,
                'file_name' => $hotelName,
                'file_extension' => $extension,
                'vendor_user_id' => $user->id
            ];
        });

        // Store the photos in the media_files table
        MediaFile::query()->upsert($photos, ['vendor_user_id', 'file_name', 'file_path'], ['file_extension']);
    }
}
