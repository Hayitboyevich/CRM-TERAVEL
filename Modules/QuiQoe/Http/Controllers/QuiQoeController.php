<?php

namespace Modules\QuiQoe\Http\Controllers;

use App\Models\Application;
use App\Models\ClientPassport;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Lead;
use App\Models\LeadAgent;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Traveler;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\QuiQoe\DTO\OrderItemDTO;
use Modules\QuiQoe\DTO\SaveOrderPipelineDTO;
use Modules\QuiQoe\Services\SaveOrderItemsPipeline;

class QuiQoeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return string
     */

    protected $orderItemsPipeline;

    public function __construct(SaveOrderItemsPipeline $orderItemsPipeline)
    {
        $this->orderItemsPipeline = app()->make(SaveOrderItemsPipeline::class);
    }

    public function index(Request $request)
    {
        try {
//            DB::beginTransaction();
            $string = json_decode($request->json);
            $req = $string->booking;


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
            $currency = Currency::query()->firstOrCreate(
                ['company_id' => $agent->company_id, 'currency_code' => $req->general->prices->inForeignCurrency->currency],
                ['currency_name' => $req->general->prices->inForeignCurrency->currency, 'no_of_decimal' => 2,'decimal_separator' => '.','thousand_separator' => ',']);
            $companyCurrency = Currency::query()->where('company_id', $company->id)->where('id', $company->currency_id)->first();
            $totalPrice = $req->general->prices->inForeignCurrency->gross / $currency->exchange_rate;

            $order = Order::create([
                'lead_id' => $application->id,
                'order_date' => now(),
                'company_id' => $agent->company_id,
                'adults_count' => $req->general?->adults,
                'children_count' => $req->general?->children,
                'total' => $totalPrice,
                'currency_id' => $companyCurrency->id,
                'client_id' => $application->client_id,
                'application_id' => $application->id,
            ]);

            $pipes = $this->orderItemsPipeline->pipes(['request' => $req, 'order' => $order]);
            foreach ($req->passengers as $passenger) {
                $serial = $passenger->serial . '' . $passenger->number;
                $pass = ClientPassport::query()->where('passport_serial_number', $serial)->first();
                if (empty($pass)) {
                    $user = User::create([
                        'firstname' => $passenger?->firstName,
                        'lastname' => $passenger?->lastName,
                        'mobile' => $passenger?->phone ?? ' ',
                        'birthday' => !empty($passenger->birthday) ? Carbon::createFromFormat('d.m.Y', $passenger->birthday)->format('Y-m-d') : null,
                        'gender' => $passenger->sex,
                        'company_id' => $agent->company_id,
                    ]);

                    $clientPassport = ClientPassport::create([
                        'client_id' => $user->id,
                        'passport_serial_number' => $serial,
                        'passport_type' => $passenger?->docType === 'internationalPassport' ? 'touristic' : 'passport',
                        'first_name' => $user->firstname,
                        'last_name' => $user->lastname,
                        'date_of_expiry' => !empty($passenger->expire) ? Carbon::createFromFormat('d.m.Y', $passenger->expire)->format('Y-m-d') : null,
                        'date_of_birth' => !empty($passenger->birthday) ? Carbon::createFromFormat('d.m.Y', $passenger->birthday)->format('Y-m-d') : null,
                        'nationality' => $passenger->nationality,
                        'gender' => $passenger->sex,
                    ]);
                    Traveler::create([
                        'user_id' => $user->id,
                        'application_id' => $application->id,
                        'company_id' => $agent->company_id,
                    ]);
                } else {
                    Traveler::create([
                        'user_id' => $pass->client_id,
                        'application_id' => $application->id,
                        'company_id' => $agent->company_id,
                    ]);
                }
            }
        }catch (\Exception $e){
            Log::error($e->getMessage());
            dd([$e->getMessage(), $e->getFile(), $e->getLine()]);
        }

        return redirect()->route('applications.edit', $application->id);
    }

    public function webhookFromEmail(Request $request)
    {
        try {
            $mobile = $request->manager['phone'];
            $agent = LeadAgent::whereHas('user', function ($query) use ($mobile) {
                $query->where('mobile', $mobile);
            })->first();

            if (!empty($request->customer['phone'])) {
                $client = $request->customer;
                $user = User::query()->firstOrCreate(
                    ['mobile' => $client['phone']],
                    ['firstname' => $client['name'] ?? null,
                        'lastname' => $client['surname'] ?? null,
                        'email' => $client['email'] ?? null,
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

            $item = $request->items[0];
            $currency = Currency::query()->firstOrCreate(
                ['company_id' => $agent->company_id, 'currency_code' => $item['currency']],
                ['currency_name' => $item['currency'], 'no_of_decimal' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',']);
//        $totalPrice = $currency->exchange_rate * $item['price'];

            $order = Order::create([
                'lead_id' => $application->id,
                'order_date' => now(),
                'company_id' => $agent->company_id,
                'adults_count' => $item['adults'] ?? null,
                'children_count' => $item['children'] ?? null,
                'total' => $item['price'] ?? null,
                'currency_id' => $currency->id,
                'client_id' => $application->client_id,
                'application_id' => $application->id,
            ]);

            $flightProductId = Product::query()->where('name', 'LIKE', '%flights')->first();
            $hotelProductId = Product::query()->where('name', 'LIKE', '%hotel')->first();
            $orderItemsDto = new SaveOrderPipelineDTO($request);
            $orderItemsDto->setData(
                (new OrderItemDTO(
                    orderId: $order->id,
                    productId: $hotelProductId?->id,
                    itemName: 'Отель',
                    itemSummary: "Hotel name: " . $item['name'] . ". Room type: " . $item['room'] . ". Nights: " . $item['nights'],
                    quantity: 1,
                    unitPrice: 0,
                    amount: 0,
                    adultsCount: $item['adults'] ?? null,
                    childrenCount: $item['children'] ?? null,
                    infantCount: $item['infants'] ?? null,
                    dateFrom: !empty($item['dateStart']) ? Carbon::createFromFormat('d.m.Y', $item['dateStart'])->format('Y-m-d') : null,
                    dateTo: !empty($item['dateStart']) ? Carbon::createFromFormat('d.m.Y', $item['dateStart'])->format('Y-m-d') : null,
                    departureTime: null,
                    arrivalTime: null,
                    nights: $item['nights'] ?? null,
                    createdAt: now(),
                    updatedAt: now(),
                    currencyId: null,
                    countryId: null,
                ))->toArray());

            if (array_key_exists('flight', $item) && array_key_exists('sectors', $item['flight'])) {
                $flights = $item['flight']['sectors'];

                foreach ($flights as $flight) {
                    $orderItemsDto->setData((new OrderItemDTO(
                        orderId: $order->id,
                        productId: $flightProductId?->id,
                        itemName: 'Авиабилет',
                        itemSummary: 'Flight number: ' . $flight['segments'][0]['flightNumber'],
                        quantity: 0,
                        unitPrice: 0,
                        amount: 0,
                        adultsCount: null,
                        childrenCount: null,
                        infantCount: null,
                        dateFrom: !empty($flight['segments'][0]['departureDate']) ? Carbon::createFromFormat('d.m.Y', $flight['segments'][0]['departureDate'])->format('Y-m-d') : null,
                        dateTo: !empty($flight['segments'][0]['arrivalDate']) ? Carbon::createFromFormat('d.m.Y', $flight['segments'][0]['arrivalDate'])->format('Y-m-d') : null,
                        departureTime: $flight['segments'][0]['departureTime'],
                        arrivalTime: $flight['segments'][0]['arrivalTime'],
                        nights: null,
                        createdAt: now(),
                        updatedAt: now(),
                        currencyId: null,
                        countryId: null,
                    ))->toArray());
                }
            }

            OrderItems::query()->insert($orderItemsDto->getOrderItems());
        } catch (\Exception $e) {
            Log::error(['error'=> $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
        }

        return 'ok';
    }
}
