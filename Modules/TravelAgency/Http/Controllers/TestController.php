<?php

namespace Modules\TravelAgency\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SmsSenderJob;
use Exception;

class TestController extends Controller
{

    public function test()
    {
        try {
            (new SmsSenderJob())->handle();
        } catch (Exception $exception) {
            dd($exception);
        }
    }
}
