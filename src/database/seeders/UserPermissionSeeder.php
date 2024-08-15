<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Carbon\Carbon;

class UserPermissionSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        // ユーザー作成
        $administrator = User::create([
            'name' => '管理者',
            'email' => 'admin@sample.com',
            'password' => bcrypt('password'),
            'email_verified_at' => $now,
        ]);

        $owner = User::create([
            'name' => '店舗代表者',
            'email' => 'owner@sample.com',
            'password' => bcrypt('password'),
            'email_verified_at' => $now,
        ]);

        $user = User::create([
            'name' => '利用者',
            'email' => 'user@sample.com',
            'password' => bcrypt('password'),
            'email_verified_at' => $now,
        ]);

        // ロール作成
        $adminRole = Role::create(['name' => 'admin']);
        $ownerRole = Role::create(['name' => 'owner']);
        $userRole = Role::create(['name' => 'user']);

        // 権限作成
        $registerPermission = Permission::create(['name' => 'register']);
        $ownerPermission = Permission::create(['name' => 'owner']);
        $userPermission = Permission::create(['name' => 'user']);


        // admin役割にregister権限を付与
        $adminRole->givePermissionTo($registerPermission);
        $ownerRole->givePermissionTo($ownerPermission);
        $userRole->givePermissionTo($userPermission);

        // 管理者にadminRoleを割り当て
        $administrator->assignRole($adminRole);

        //店舗代表者にownerRoleを割り当て
        $owner->assignRole($ownerRole);

        //一般ユーザーにuserRoleを割り当て
        $user->assignRole($userRole);
    }
}
