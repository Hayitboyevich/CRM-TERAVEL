<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\StorePartnerReqeust;
use App\Models\IntegrationPartner;
use App\Models\Product;
use Illuminate\Http\Response;

class ProductSettingController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
//            abort_403(!in_array('partner', $this->user->modules));
            return $next($request);
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $this->addPermission = user()->permission('add_product');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        return view('products.create-product-model', $this->data);
    }

    public function store(StorePartnerReqeust $request)
    {
        $this->addPermission = user()->permission('add_product');
        abort_403(!in_array($this->addPermission, ['all', 'added']));
        $data = $request->validated();
        $product = new Product();
        $data['company_id'] = company()->id;
        $product->fill($data);
        $product->save();

        $products = Product::query()->get();

        $list = '<option value="">--</option>';

        foreach ($products as $product) {

            $list .= '<option value="' . $product->id . '"> ' . $product->name . ' </option>';
        }

        return Reply::successWithData(__('messages.recordSaved'), ['data' => $list]);

    }


}
