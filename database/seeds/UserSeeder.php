<?php

use App\Repositories\Eloquent\UserRepository;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var UserRepository $userRepository */
        $userRepository = app(UserRepository::class);
        $fakeUsers = $userRepository->fakeUsers();

        foreach($fakeUsers as $fakeUser) {
            $userRepository->save($fakeUser);
        }
    }
}
