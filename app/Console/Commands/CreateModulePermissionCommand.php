<?php

namespace App\Console\Commands;

use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\UserPermission;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateModulePermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-module-permission {permission_name} {module_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add permission to module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $moduleName = $this->argument('module_name');
            $permissionName = $this->argument('permission_name');
            $module = Module::query()->where('module_name', $moduleName)->first();

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


        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
    }
}
