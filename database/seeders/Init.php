<?php

namespace BytePlatform\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class Init extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $roleModel = (config('byteplatform.model.role', \BytePlatform\Models\Role::class));
        $userModel = (config('byteplatform.model.user', \BytePlatform\Models\User::class));
        $roleAdmin = new $roleModel;
        $roleAdmin->name = $roleModel::SupperAdmin();
        $roleAdmin->slug = $roleModel::SupperAdmin();
        $roleAdmin->status = true;
        $roleAdmin->save();
        $userAdmin = new $userModel;
        $userAdmin->name = env('BITSUDO_PlATFORM_FULLNAME', "NGUYEN VAN HAU");
        $userAdmin->email = env('BITSUDO_PlATFORM_EMAIL', "admin@hau.xyz");
        $userAdmin->password = env('BITSUDO_PlATFORM_PASSWORD', "AdMin@123");
        $userAdmin->status = 1;
        $userAdmin->save();
        $userAdmin->roles()->sync([$roleAdmin->id]);
    }
}
