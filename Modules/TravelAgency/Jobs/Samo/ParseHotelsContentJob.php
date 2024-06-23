<?php

namespace Modules\TravelAgency\Jobs\Samo;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Hotel\Models\Hotel;
use Modules\TravelAgency\Client\Curl;
use Modules\TravelAgency\DOM\HtmlDomAdapter;

class ParseHotelsContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
        $hotelName = preg_replace('/\s*\d+\*/', '', $dom->find('.td.left h2')->text());
        $text = $dom->find('.html_editor.light.action p')->each(function ($node) {
            return $node->html();
        });
        $text = implode(' ', $text);


        $hotel = Hotel::where('vendor_user_id', $user->id)
            ->where('title', $hotelName)
            ->first();

        if ($hotel) {
            $hotel->content = $text;
            $hotel->save();
        }

    }

}
