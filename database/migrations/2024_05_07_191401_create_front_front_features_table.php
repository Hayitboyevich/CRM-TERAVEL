<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('front_features', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('language_setting_id')->nullable();
            $table->foreign('language_setting_id')->references('id')->on('language_settings')->onDelete('cascade')->onUpdate('cascade');
            $table->string('title')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->enum('status', ['enable', 'disable'])->default('enable');
            $table->timestamps();
        });

        Schema::table('features', function (Blueprint $table) {
            $table->unsignedBigInteger('front_feature_id')->nullable()->default(null);
            $table->foreign('front_feature_id')->references('id')->on('front_features')->onDelete('cascade')->onUpdate('cascade');
        });


        $frontDetail = \App\TrFrontDetail::first();
        $features = \App\Feature::all();

        $allTaskFeature = $features->filter(function ($value, $key) {
            return $value->type == 'task';
        });

        $allBillsFeature = $features->filter(function ($value, $key) {
            return $value->type == 'bills';
        });

        $allTeamatesFeature = $features->filter(function ($value, $key) {
            return $value->type == 'team';
        });

        if($frontDetail)
        {
            $frontFeature = new \App\FrontFeature();
            $frontFeature->title = $frontDetail->task_management_title;
            $frontFeature->description = $frontDetail->task_management_detail;
            $frontFeature->language_setting_id = $frontDetail->language_setting_id;
            $frontFeature->save();

            foreach($allTaskFeature as $taskFeature){
                $taskFeature->front_feature_id = $frontFeature->id;
                $taskFeature->save();;
            }

            $frontFeature = new \App\FrontFeature();
            $frontFeature->title = $frontDetail->manage_bills_title;
            $frontFeature->description = $frontDetail->manage_bills_detail;
            $frontFeature->language_setting_id = $frontDetail->language_setting_id;
            $frontFeature->save();

            foreach($allBillsFeature as $billFeature){
                $billFeature->front_feature_id = $frontFeature->id;
                $billFeature->save();
            }

            $frontFeature = new \App\FrontFeature();
            $frontFeature->title = $frontDetail->teamates_title;
            $frontFeature->description = $frontDetail->teamates_detail;
            $frontFeature->language_setting_id = $frontDetail->language_setting_id;
            $frontFeature->save();

            foreach($allTeamatesFeature as $teamatesFeature){
                $teamatesFeature->front_feature_id = $frontFeature->id;
                $teamatesFeature->save();
            }
        }

        $companies =  Company::withoutGlobalScope('active')->get();
        $roles = ['client'];

        foreach ($companies as $company) {
            // create admin, employee and client module settings
            foreach ($roles as $role) {
                \App\ModuleSetting::firstOrCreate([
                    'module_name' => 'products',
                    'status' => 'active',
                    'type' => $role,
                    'company_id' => $company->id
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_front_features');
    }
};
