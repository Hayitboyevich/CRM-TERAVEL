<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\Logs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    public function create(Request $request)
    {
        try {
            $source = LeadSource::query()->where('type', $request->input('source'))->where('company_id', 1)->first();
            if ($request->input('mobile') !== null){
                $mobile = str_replace('+998', '', $request->input('mobile'));
                $client = User::query()->where('mobile', $mobile)->where('company_id', 1)->first();

                if ($client === null) {
                    $client = User::query()->create([
                        'name' => $request->input('name'),
                        'mobile' => $mobile,
                        'password' => bcrypt($mobile),
                        'company_id' => 1,
                    ]);
                }
            }
            $lead = Lead::query()->create([
                'client_name' => $request->input('name'),
                'mobile' => $request->input('mobile'),
                'note' => $request->input('note'),
                'client_id' => $client->id ?? null,
                'status_id' => 17,
                'column_priority' => 0,
                'source_id' => $source->id ?? null,
                'company_id' => 1,
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            // Catch any database related exceptions
            Log::error('Database error: ' . $e->getMessage());

        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());

        }

        return response()->json(['message' => 'Lead stored successfully']);
    }

}
