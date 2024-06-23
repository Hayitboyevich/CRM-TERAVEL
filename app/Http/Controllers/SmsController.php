<?php

namespace App\Http\Controllers;

use App\DataTables\SmsDataTable;
use App\Helper\Reply;
use App\Http\Requests\StoreSmsRequest;
use App\Models\SmsMailing;
use App\Models\UniversalSearch;
use App\Models\User;
use App\Scopes\ActiveScope;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SmsController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(SmsDataTable $dataTable)
    {
        $this->pageTitle = 'SMS';

        return $dataTable->render('sms.index', $this->data);
    }

    public function store(StoreSmsRequest $request)
    {
        $data = $request->validated();
        try {
            $sms = new SmsMailing();
            $data['user_id'] = json_encode($data['user_id']);
            $data['delivery_date'] = date('Y-m-d H:i:s', strtotime($data['delivery_date'] . ' ' . $data['delivery_time']));

            $sms->fill($data);
            $sms->save();

        } catch (Exception $exception) {
            return Reply::error($exception->getMessage(), ['redirectUrl' => route('sms.index')]);
        }
        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => route('sms.index')]);
    }

    public function create()
    {
        $this->pageTitle = 'Создать СМС';
        $this->clients = User::allClients();
        return view('sms.create', $this->data);
    }

    /**
     * XXXXXXXXXXX
     *
     * @return Response
     */
    public function applyQuickAction(Request $request)
    {
        switch ($request->action_type) {
            case 'delete':
                $this->deleteRecords($request);
                return Reply::success(__('messages.deleteSuccess'));
            case 'change-status':
                $this->changeStatus($request);
                return Reply::success(__('messages.updateSuccess'));
            default:
                return Reply::error(__('messages.selectAction'));
        }
    }

    protected function deleteRecords($request)
    {
        abort_403(user()->permission('delete_sms') !== 'all');
        $mails = SmsMailing::withoutGlobalScope(ActiveScope::class)->whereIn('id', explode(',', $request->row_ids))->get();
        $mails->each(function ($user) {
            $this->deleteSms($user);
        });
        return true;
    }

    private function deleteSms(SmsMailing $sms)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $sms->id)->where('module_type', 'sms')->get();

        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }


        $sms->delete();
    }

    public function destroy($id)
    {
        $this->sms = SmsMailing::findOrFail($id);
        $this->deletePermission = user()->permission('delete_sms');

        abort_403(
            !($this->deletePermission == 'all'
            )
        );

        $this->deleteSms($this->sms);

        return Reply::success(__('messages.deleteSuccess'));
    }

    public function edit(SmsMailing $sms)
    {
        $this->pageTitle = 'Обновить СМС';
        $this->sms = $sms;
        $this->clients = User::allClients();
        $this->view = 'sms.edit';


        return view($this->view, $this->data);
    }

    public function show($id)
    {
        $this->sms = SmsMailing::withoutGlobalScope(ActiveScope::class)->findOrFail($id);
        $this->viewPermission = user()->permission('view_sms');
        $this->users = User::query()
            ->whereIn('id', json_decode($this->sms->user_id, true))
            ->get()
            ->pluck('name')
            ->toArray();
        $this->pageTitle = ucfirst($this->sms?->name);

        $this->text = implode(' ', $this->users);

        $this->view = 'sms.ajax.show';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('sms.show', $this->data);

    }
}
