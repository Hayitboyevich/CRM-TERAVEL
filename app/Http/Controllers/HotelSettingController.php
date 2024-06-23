<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\HotelRequest;
use App\Models\Hotel;
use App\Models\IntegrationState;
use Illuminate\Http\Response;

class HotelSettingController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
//        $this->addPermission = user()->permission('add_hotel');
//        abort_403(!in_array($this->addPermission, ['all', 'added']));
        $this->countries = IntegrationState::query()->get();

        return view('hotel-settings.create-hotel-modal', $this->data);
    }

    public function store(HotelRequest $request)
    {
//        $this->addPermission = user()->permission('add_lead_agent');
//        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $data = $request->validated();
        $hotel = new Hotel();
        $data['company_id'] = company()->id;
        $hotel->fill($data);
        $hotel->save();

        $hotels = Hotel::query()->get();

        $list = '<option value="">--</option>';

        foreach ($hotels as $hotel) {

            $list .= '<option "value="' . $hotel->id . '"> ' . $hotel->name . ' </option>';
        }

        return Reply::successWithData(__('messages.recordSaved'), ['data' => $list]);

    }
}