<?php

use Illuminate\Database\Seeder;
use App\User;

class SetSuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmins = ['wilson_twm'];

        foreach($superadmins as $superadmin) {
            $user = User::where('username', $superadmin)
                        ->where('role', '<>', User::ROLE_SUPERADMIN)
                        ->first();

            if($user) {
                $user->role = User::ROLE_SUPERADMIN;
                $user->save();
            }

        }
    }
}
