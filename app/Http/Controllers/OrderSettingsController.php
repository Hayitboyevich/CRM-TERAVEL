<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\BaseModel;
use App\Models\TourType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderSettingsController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
//        $this->middleware(function ($request, $next) {
//            abort_403(!in_array('leads', $this->user->modules));
//            return $next($request);
//        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
//        $this->addPermission = user()->permission('add_lead_sources');
//        abort_403(!in_array($this->addPermission, ['all', 'added']));

        return view('application-settings.create-order-type');

    }


    public function store(Request $request)
    {
//        $this->addPermission = user()->permission('add_lead_sources');
//        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $source = new TourType();
        $source->name = $request->name;
        $source->save();

        $leadSource = TourType::query()->get();

        $options = BaseModel::options($leadSource, $source, 'name');

        return Reply::successWithData(__('messages.recordSaved'), ['data' => $options]);

    }
}