<?php

use Illuminate\Database\Seeder;
use App\User;
use App\UserProfile;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Create default admin*/
        factory(App\User::class)->states('admin-role')
            ->create(['email' => 'admin@local.dev'])
            ->each(function ($user) {
            $user->profile()->save(factory(UserProfile::class)->make());
        });

        /*Create default user*/
        factory(App\User::class)->states('user-role')
            ->create(['email' => 'user@local.dev'])
            ->each(function ($user) {
                $user->profile()->save(factory(UserProfile::class)->make());
            });

        /*Create 5 admins*/
        factory(App\User::class, 5)->states('admin-role')
            ->create()
            ->each(function ($user) {
            $user->profile()->save(factory(UserProfile::class)->make());
        });

        /*Create 20 users*/
        factory(App\User::class, 20)->states('user-role')
            ->create()
            ->each(function ($user) {
                $user->profile()->save(factory(UserProfile::class)->make());
            });
    }
}
