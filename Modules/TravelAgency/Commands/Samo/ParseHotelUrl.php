<?php

namespace Modules\TravelAgency\Commands\Samo;

use Illuminate\Console\Command;
use Modules\Location\Models\Location;
use Modules\Location\Models\LocationVendor;
use Modules\Parser\Client\Curl;
use Modules\Parser\DOM\HtmlDomAdapter;
use Modules\Parser\DOM\XmlDomAdapter;
use Modules\Parser\Jobs\Samo\ParseHotelUrlJob;

class ParseHotelUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:url';

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

        $states = Location::query()->where('parent_id', '=', null)->get('nameAlt');
        foreach ($states as $state) {
            $name = str_replace(' ', '', strtolower($state->nameAlt));
            $url = "https://kompastour.com/uz/rus/hotels/{$name}/";
            ParseHotelUrlJob::dispatch($url)->onQueue('url');
        }

    }
}
