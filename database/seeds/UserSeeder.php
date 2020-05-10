<?php

use App\User;
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
        User::create([
            "name" => "Luan Andrade Loose11111",
            "email" => "luan11111@teste.com",
            "password" => "teste",
            "cpf" => "11111",
            "phone" => "11111",
            "money" => "200,0",
            "seller" => [
                "cnpj" => "11111",
                "razao_social" => "teste luan",
                "nome_fantasia" => "teste luan",
                "username" => "luanloose11111"
            ],
            "consumer" => [
                "username" => "luan11111"
            ]
        ]);

        User::create([
            "name" => "Nicole11111",
            "email" => "nicole11111@teste.com",
            "password" => "teste",
            "cpf" => "11111",
            "phone" => "11111",
            "money" => "200,0",
            "seller" => [
                "cnpj" => "00370000023055076",
                "razao_social" => "teste nicole",
                "nome_fantasia" => "teste nicole",
                "username" => "nicole11111",
            ],
            "consumer" => [
                "username" => "nicole11111",
                
            ]
        ]);
        User::create([
            "name" => "Nicole22222",
            "email" => "nicole22222@teste.com",
            "password" => "teste",
            "cpf" => "22222",
            "phone" => "22222",
            "money" => "200,0",
            "seller" => [
                "cnpj" => "00370000023055076",
                "razao_social" => "teste nicole",
                "nome_fantasia" => "teste nicole",
                "username" => "nicole22222",
            ],
            "consumer" => [
                "username" => "nicole22222",
                
            ]
        ]);
        User::create([
            "name" => "Nicole33333",
            "email" => "nicole33333@teste.com",
            "password" => "teste",
            "cpf" => "33333",
            "phone" => "33333",
            "money" => "200,0",
            "seller" => [
                "cnpj" => "00370000023055076",
                "razao_social" => "teste nicole",
                "nome_fantasia" => "teste nicole",
                "username" => "nicole33333",
            ],
            "consumer" => [
                "username" => "nicole33333",
                
            ]
        ]);
    }
}
