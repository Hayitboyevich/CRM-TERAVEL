<?php

namespace Modules\QuiQoe\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Models\LeadAgent;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\QuiQoe\DTO\OrderItemDTO;

class SelectionToursController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return string
     */

    public function __construct()
    {
    }

    public function index(Request $request)
    {
        try {
            $string = json_decode($request->json);
            $agent = null;
            $user = null;

            $mobile = $string->manager->phone;
            $agent = LeadAgent::whereHas('user', function ($query) use ($mobile) {
                $query->where('mobile', $mobile);
            })->first();

            if (!empty($string->client?->phone)) {
                $client = $string->client;
                $user = User::query()->firstOrCreate(
                    ['mobile' => $client->phone],
                    ['firstname' => $client->name ?? null,
                        'lastname' => $client->surname ?? null,
                        'birthday' => !empty($client->dob) ? Carbon::createFromFormat('d.m.Y', $client->dob)->format('Y-m-d') : null,
                        'email' => $client->email ?? null,
                        'company_id' => $agent->company_id,
                    ]);
            } else {
                $user = User::create([
                    'company_id' => $agent->company_id,
                ]);
            }

            $application = Application::create([
                'company_id' => $agent->company_id,
                'client_id' => $user->id,
                'client_name' => $user?->firstname ?? '',
                'column_priority' => 0,
                'status_id' => 1,
                'agent_id' => $agent->id,
            ]);

            $company = Company::query()->find($agent->company_id);
            $currencies = Currency::all();
            $orderTotalPrice = 0;
            $adultsCount = 0;
            $children = 0;
            foreach ($string->items as $item) {
                $itemCurrency = Currency::query()->firstOrCreate(
                    ['company_id' => $agent->company_id, 'currency_code' => $item->currency],
                    ['currency_name' => $item->currency, 'no_of_decimal' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',']);
                $companyCurrency = Currency::query()->where('company_id', $company->id)->where('id', $company->currency_id)->first();
                $orderTotalPrice += ($item->price / $itemCurrency->exchange_rate);

                $adultsCount += $item->adults;
                $children += $item->children;
            }

            $order = Order::create([
                'lead_id' => $application->id,
                'order_date' => now(),
                'company_id' => $agent->company_id,
                'adults_count' => $adultsCount,
                'children_count' => $children,
                'total' => $orderTotalPrice,
                'currency_id' => $companyCurrency?->id,
                'client_id' => $application->client_id,
                'application_id' => $application->id,
            ]);

            $orderItems = [];
            $productsCollection = Product::all();
            $countries = Country::all();

            foreach ($string->items as $item) {
                $name = strtolower($item->product_type);
                $product = $productsCollection->first(function ($product) use ($name) {
                    return stripos($product->name, $name) !== false;
                });

                $currency = $currencies->where('company_id', $agent->company_id)->where('currency_code', $item->currency)->first();
                $country = $countries->where('name_ru', $item->country)->first();

                $orderItems[] = ((new OrderItemDTO(
                    orderId: $order->id,
                    productId: $product?->id,
                    itemName: $product->name,
                    itemSummary: '',
                    quantity: 0,
                    unitPrice: $item?->price,
                    amount: 1,
                    adultsCount: $item?->adults,
                    childrenCount: $item?->children,
                    infantCount: null,
                    dateFrom: !empty($item->checkin) ? Carbon::createFromFormat('d.m.Y', $item->checkin)->format('Y-m-d') : null,
                    dateTo: null,
                    departureTime: null,
                    arrivalTime: null,
                    nights: $item->nights,
                    createdAt: now(),
                    updatedAt: now(),
                    currencyId: empty($currency->id) ? null : $currency->id,
                    countryId: $country?->id ?? null,
                ))->toArray());
            }

            $orderItems = OrderItems::insert($orderItems);
        } catch (Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }
        return redirect()->route('applications.edit', $application->id);
    }

}
