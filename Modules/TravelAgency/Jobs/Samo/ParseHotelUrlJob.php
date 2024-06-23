<?php

namespace Modules\TravelAgency\Jobs\Samo;

use App\Models\HotelsUrl;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Location\Models\Vendor;
use Modules\TravelAgency\Client\Curl;
use Modules\TravelAgency\DOM\HtmlDomAdapter;

class ParseHotelUrlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $page_selector;
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
        $this->page_selector = '.pager_block .pager_item';
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
        $response = true;
        $page = 1;
        $hotelsUrl = [];
        while ($response) {
            $content = (new Curl())->init($this->data)->method('get', ['page' => $page])->execute();
            $dom = new HtmlDomAdapter($content->getBody());
            $page_number = 1;
            $response = ($dom->find($this->selector)->count() > 0);
            if (isset($this->page_selector)) {
                $pagination_numbers = $dom->find($this->page_selector)->each(function ($node) {
                    return $node->text();
                });
                if (!empty($pagination_numbers)) {
                    $page_number = max(array_filter($pagination_numbers, 'is_numeric'));
                    $response = $page <= $page_number;
                }
            }

            $hrefs = $dom->find($this->selector)->each(function ($node) use ($user) {
                return [
                    'href' => 'https://kompastour.com' . $node->attr('href'),
                    'name' => $node->text(),
                    'vendor_user_id' => $user->id
                ];
            });
            $page++;
            $hotelsUrl = array_merge($hotelsUrl, $hrefs);
        }

        HotelsUrl::query()->upsert($hotelsUrl, ['href'], ['name', 'vendor_user_id']);


    }
}
