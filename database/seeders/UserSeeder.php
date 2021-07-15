<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            ['name' => 'stepan7', 'email' => 'fenixrnd@mail.ru', 'password' => Hash::make("Test123321"), "role" => "admin", "phone" => null],
        );

        User::create(
            ["name" => "admin", "email" => "agro_st@mail.ru", "password" => Hash::make("agro_st"), "role" => "admin", "phone" => null]
        );

        User::create(
            ['name' => 'Диллер тест', 'email' => 'testdealer@mail.ru', 'password' => Hash::make("Test123321"), "role" => "dealer", "phone" => "8800999"]
        );
        User::create(
            ['name' => 'Клиент тест', 'email' => 'client@mail.ru', 'password' => Hash::make("Test123321"), "role" => "client", "phone" => "88005553555"]
        );
    }
}
