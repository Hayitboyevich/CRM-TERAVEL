<?php

namespace App\Http\Controllers\Applications;

use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\StoreTravellerRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Application;
use App\Models\ClientDocument;
use App\Models\ClientPassport;
use App\Models\Company;
use App\Models\LeadAgent;
use App\Models\LeadSource;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Traveler;
use App\Models\User;
use App\Scopes\ActiveScope;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CustomClientController extends AccountBaseController
{
    public function __construct()
    {
        $this->pageTitle = "Clients";
        parent::__construct();
    }

    public function createTraveller($leadId)
    {
        $this->company = Company::where('id', company()->id)->firstOrFail();
        $this->addPermission = user()->permission('add_clients');
        $this->leadId = $leadId;
        abort_403(!in_array($this->addPermission, User::ALL_ADDED_BOTH));

        $this->pageTitle = __('app.addClient');

        $this->countries = countries();
        $this->sources = LeadSource::all();
        $this->leadAgents = LeadAgent::all();


        if (request()->ajax()) {
            $html = view('applications.travellers.create', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'applications.travellers.create';

        return view('applications.clients.template', $this->data);
    }

    public function searchClientView(Application $application)
    {
        $this->pageTitle = 'Search clients';
        $this->applicationId = $application->id;
        return view('applications.clients.search', $this->data);
    }

    public function searchTraveller(Application $application)
    {
        $this->pageTitle = 'Search clients';
        $this->applicationId = $application->id;
        return view('applications.travellers.search', $this->data);
    }

    public function addUser(Application $application, User $user)
    {
        $application->client_id = $user->id;
        $application->save();
        $order = $application->order;
        if ($order) {
            $order->client_id = $user->id;
            $order->save();
        }

        return redirect()->route('applications.clients.edit', [$application->id, $user->id]);

    }

    public function addTraveller(Application $application, User $user)
    {
        $traveller = Traveler::query()
            ->where('user_id', $user->id)
            ->where('application_id', $application->id)
            ->first();
        if ($traveller) {
            return redirect()
                ->route('applications.edit', $application->id)
                ->with('error', "Турист уже добавлен!");
        }
        $traveller = new Traveler();
        $traveller->user_id = $user->id;
        $traveller->application_id = $application->id;
        $traveller->save();

        return redirect()->route('applications.clients.edit', [$application->id, $user->id]);

    }

    public function removeUser($applicationId, $clientId)
    {
        $application = Application::query()->findOrFail($applicationId);
        $application->client_id = null;
        $application->save();
        return Reply::success(__('messages.deleteSuccess'));


    }

    public function removeTraveller($applicationId, $clientId)
    {
        Traveler::query()
            ->where('user_id', $clientId)
            ->where('application_id', $applicationId)
            ->delete();

        return Reply::success(__('messages.deleteSuccess'));


    }

    public function searchClient(Request $request)
    {
        $q = $request->q;
        $users = User::query()
            ->select([
                'users.id', 'roles.name',
                'users.company_id', 'users.firstname',
                'users.lastname', 'users.fathername',
                'users.mobile'
            ])
            ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('users.company_id', company()->id)
            ->where(function ($query) use ($q) {
                $query->where('users.firstname', 'like', '%' . $q . '%')
                    ->orWhere('users.lastname', 'like', '%' . $q . '%')
                    ->orWhere('users.fathername', 'like', '%' . $q . '%')
                    ->orWhere('users.mobile', 'like', '%' . $q . '%');
            })
//            ->where('roles.name', 'client')
            ->limit(5)
            ->get();
        return response()->json($users);
    }

    public function storeTraveller(Application $application, StoreTravellerRequest $request)
    {
        $data = $request->validated();
        $data['birthday'] = $data['birthday'] ? date('Y-m-d', strtotime($data['birthday'])) : null;
        $data['foreign']['date_of_birth']= $data['birthday'] ? date('Y-m-d', strtotime($data['birthday'])) : null;
        $data['foreign']['given_date'] = $data['foreign']['given_date'] ? date('Y-m-d', strtotime($data['foreign']['given_date'])) : null;
//        $data['passport']['given_date'] = $data['passport']['given_date'] ? date('Y-m-d', strtotime($data['passport']['given_date'])) : null;
//        $data['passport']['date_of_expiry'] = $data['passport']['date_of_expiry'] ? date('Y-m-d', strtotime($data['passport']['date_of_expiry'])) : null;
        $data['foreign']['date_of_expiry'] = $data['foreign']['date_of_expiry'] ? date('Y-m-d', strtotime($data['foreign']['date_of_expiry'])) : null;
        $data['name'] = $data['firstname'] . ' ' . $data['lastname'];
        $foreign = Arr::get($data, "foreign");
//        $passport = Arr::get($data, "passport");

        $client = new User();
        $client->fill($data);


//        $localPassport = $client->localPassport;
        $foreignPassport = $client->foreignPassport;

//        if (!$localPassport) {
//            $localPassport = new ClientPassport();
//            $localPassport->passport_type = 'passport';
//        }
        if (!$foreignPassport) {
            $foreignPassport = new ClientPassport();
            $foreignPassport->passport_type = 'touristic';

        }
//        $localPassport->fill($passport);
        $foreignPassport->fill($foreign);

        $traveler = new Traveler();
        $traveler->application_id = $application->id;

        DB::beginTransaction();
        try {
            $client->save();
            RoleUser::query()->create(
                [
                    'user_id' => $client->id,
                    'role_id' => Role::query()->where('name', '=', 'client')->first()?->id,
                ]
            );
            $traveler->user_id = $client->id;

//            $localPassport->client_id = $client->id;
            $foreignPassport->client_id = $client->id;

            $foreignPassport->save();
//            $localPassport->save();

            $traveler->save();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('applications.edit', $application->id);
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => $redirectUrl]);

    }

    public function create(Application $application)
    {
        $this->company = Company::where('id', company()->id)->firstOrFail();
        $this->application = $application;
        $this->addPermission = user()->permission('add_clients');
        abort_403(!in_array($this->addPermission, User::ALL_ADDED_BOTH));

        $this->pageTitle = __('app.addClient');

        $this->countries = countries();
        $this->sources = LeadSource::all();
        $this->leadAgents = LeadAgent::all();

        $this->view = 'applications.clients.create';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }


        return view('applications.clients.template', $this->data);
    }

    public function store(Application $application, StoreClientRequest $request)
    {
        $data = $request->validated();
        $data = collect($data)->filter()->all();
        $nameParts = [
            $data['firstname'],
            $data['lastname'] ?? null,
            $data['fathername'] ?? null
        ];
        $data['name'] = implode(' ', array_filter($nameParts));
        $client = new User();


        $foreign = Arr::get($data, "foreign");
//        $passport = Arr::get($data, "passport");

//        $foreign = $foreign->filter()->all();
//        $passport = $passport->filter()->all();
//        dd($passport, $foreign);
        unset($data['foreign']);
        unset($data['passport']);
        if (Arr::get($data, 'birthday')) {
            $data['birthday'] = date('Y-m-d', strtotime($data['birthday']));
        }

        if (isset($foreign['date_of_expiry'])) {
            $foreign['date_of_expiry'] = date('Y-m-d', strtotime($foreign['date_of_expiry']));
        }

        if (isset($foreign['given_date'])) {
            $foreign['given_date'] = date('Y-m-d', strtotime($foreign['given_date']));
        }
        $client->fill($data);

        $localPassport = $client->localPassport;
        $foreignPassport = $client->foreignPassport;

        if (!$localPassport) {
            $localPassport = new ClientPassport();
            $localPassport->passport_type = 'passport';
        }
        if (!$foreignPassport) {
            $foreignPassport = new ClientPassport();
            $foreignPassport->passport_type = 'touristic';

        }
//        $localPassport->fill($passport);
        $foreignPassport->fill($foreign);

        $localImg = $request->passport_image;
        $foreignImg = $request->foreign_passport_image;


        $role = Role::query()->where('name', 'client')->firstOrFail();

        DB::beginTransaction();
        try {
            $client->save();

            RoleUser::query()->create([
                'user_id' => $client->id,
                'role_id' => $role->id
            ]);
//            $traveler->user_id = $client->id;
            $application->client_id = $client->id;
            $order = $application?->order;
            if ($order) {
                $order->client_id = $client->id;
                $order->save();
            }


            $localPassport->client_id = $client->id;
            $foreignPassport->client_id = $client->id;
            $localPassport->save();
            $foreignPassport->save();


            if ($foreignImg) {
                $foreignDoc = new ClientDocument();
                $foreignDoc->name = 'Загранпаспорт';
                $foreignDoc->filename = $foreignImg->getClientOriginalName();
                $foreignDoc->size = $foreignImg->getSize();

                $foreignFilename = Files::uploadLocalOrS3($foreignImg, ClientDocument::FILE_PATH . '/' . $client->id);
                $foreignDoc->user_id = $client->id;
                $foreignDoc->hashname = $foreignFilename;
                $foreignDoc->save();
            }
            if ($localImg) {
                $localDoc = new ClientDocument();
                $localDoc->user_id = $client->id;
                $localDoc->name = 'Паспорт';
                $localDoc->filename = $localImg->getClientOriginalName();
                $localDoc->size = $localImg->getSize();

                $localFilename = Files::uploadLocalOrS3($localImg, ClientDocument::FILE_PATH . '/' . $client->id);
                $localDoc->user_id = $client->id;
                $localDoc->hashname = $localFilename;
                $localDoc->save();
            }


            $application->save();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('applications.edit', $application->id);
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => $redirectUrl]);

    }

    public function edit(Application $application, $client_id)
    {
        $this->company = Company::where('id', company()->id)->firstOrFail();
        $this->application = $application;

        $this->sources = LeadSource::all();
        $this->client = User::withoutGlobalScope(ActiveScope::class)
            ->with('clientDetails', 'localPassport', 'foreignPassport')->findOrFail($client_id);

        $this->editPermission = user()->permission('edit_clients');

        abort_403(!($this->editPermission == 'all' || ($this->editPermission == 'added' && $this->client->clientDetails->added_by == user()->id) || ($this->editPermission == 'both' && $this->client->clientDetails->added_by == user()->id)));

        $this->countries = countries();


        $this->pageTitle = __('app.update') . ' ' . __('app.client');


        if (request()->ajax()) {
            $html = view('applications.clients.edit', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'applications.clients.edit';

        return view('applications.clients.template', $this->data);
    }

    public function update(Application $application, $client, UpdateClientRequest $request)
    {
        $data = $request->validated();

//        $data['birthday'] = strtotime($data['birthday']);
        $data['client_name'] = ($data['firstname'] ?? '') . ' ' . ($data['lastname'] ?? '');

        $application->fill($data);

//        $application->client_name = $data['client_name'];
        $client = User::withoutGlobalScope(ActiveScope::class)
            ->with('clientDetails', 'localPassport', 'foreignPassport')->findOrFail($client);
        $client->fill($data);

        $foreign = Arr::get($data, "foreign");
//        $passport = Arr::get($data, "passport");

        $foreign['given_date'] = isset($foreign['given_date']) ? date('Y-m-d', strtotime($foreign['given_date'])) : null;
        $foreign['date_of_birth'] = isset($foreign['date_of_birth']) ? date('Y-m-d', strtotime($foreign['date_of_birth'])) : null;
        $foreign['date_of_expiry'] = isset($foreign['date_of_expiry']) ? date('Y-m-d', strtotime($foreign['date_of_expiry'])) : null;

        $localPassport = $client->localPassport;
        $foreignPassport = $client->foreignPassport;
        if (!$client->localPassport) {
            $localPassport = new ClientPassport();
            $localPassport->passport_type = 'passport';
        }
        if (!$client->foreignPassport) {
            $foreignPassport = new ClientPassport();
            $foreignPassport->passport_type = 'touristic';
        }
//        $localPassport->fill($passport);

//        unset($passport['date_of_expire']);
        $foreignPassport->fill($foreign);

        if ($request->passport_image) {
            $localPassDoc = new ClientDocument();
            $filename = Files::uploadLocalOrS3($request->passport_image, ClientDocument::FILE_PATH . '/' . $client->id);
            $localPassDoc->name = 'Паспорт';
            $localPassDoc->filename = $request->passport_image->getClientOriginalName();
            $localPassDoc->hashname = $filename;
            $localPassDoc->size = $request->passport_image->getSize();
            $localPassDoc->user_id = $client->id;
            $localPassDoc->save();
        }
        if ($request->foreign_passport_image) {
            $foreignPassDoc = new ClientDocument();
            $filename = Files::uploadLocalOrS3($request->foreign_passport_image, ClientDocument::FILE_PATH . '/' . $client->id);
            $foreignPassDoc->name = 'Загранпаспорт';
            $foreignPassDoc->filename = $request->foreign_passport_image->getClientOriginalName();
            $foreignPassDoc->hashname = $filename;
            $foreignPassDoc->size = $request->foreign_passport_image->getSize();
            $foreignPassDoc->user_id = $client->id;
            $foreignPassDoc->save();
        }

        DB::beginTransaction();
        try {
            $client->save();
            $localPassport->client_id = $client->id;
            $foreignPassport->client_id = $client->id;

            $localPassport->save();
            $foreignPassport->save();
            $application->save();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('applications.edit', $application);
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => $redirectUrl]);

    }
}
