<?php

namespace App\Console\Commands;

use App\Models\Module;
use App\Models\ModuleSetting;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\UserPermission;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-module {module_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        DB::beginTransaction();
        try {
            $moduleName = $this->argument('module_name');
            $module = Module::query()->firstOrCreate([
                'module_name' => ($moduleName)
            ],
                [
                    'module_name' => ($moduleName),
                    'description' => mb_ucfirst($moduleName) . ' module'
                ]
            );

            ModuleSetting::query()->firstOrCreate([
                'company_id' => 1,
                'module_name' => $moduleName,
                'status' => 'active',
                'type' => 'employee'
            ]);
            ModuleSetting::query()->firstOrCreate([
                'company_id' => 1,
                'module_name' => $moduleName,
                'status' => 'active',
                'type' => 'client'
            ]);
            ModuleSetting::query()->firstOrCreate([
                'company_id' => 1,
                'module_name' => $moduleName,
                'status' => 'active',
                'type' => 'admin'
            ]);
            $permissionList = ['add', 'edit', 'delete', 'view'];


            foreach ($permissionList as $perm) {
                $permissionName = $perm . '_' . strtolower($moduleName);

                $data = [
                    'name' => $permissionName,
                    'display_name' => strtoupper($permissionName),
                    'module_id' => $module->id,
                    'is_custom' => 1,
                ];

                $permission = Permission::query()->firstOrCreate($data, array_merge($data, [
                    'allowed_permissions' => Permission::ALL_4_ADDED_1_OWNED_2_BOTH_3_NONE_5
                ]));

                PermissionRole::query()->create([
                    'permission_id' => $permission->id,
                    'permission_type_id' => 4,
                    'role_id' => 1
                ]);
                UserPermission::query()->create([
                    'permission_id' => $permission->id,
                    'permission_type_id' => 4,
                    'user_id' => 1
                ]);
            }


        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();

    }
}
