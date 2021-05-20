<?php

namespace Tests\Feature\User;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testGetAllUsers()
    {
        $response = $this->call('GET', '/user');

        $this->assertEquals(200, $response->status());
    }

    public function testSaveUser(): void 
    {
        DB::beginTransaction();

        $response = $this->call('POST', 
            '/user', 
            [
                'name' => 'Luan Andrade',
                'cpf' => '12312312312',
                'email' => 'teste@teste.com',
                'password' => 'luan@teste',
                'money' => '400.00',
                'type' => 'consumer'
            ]
        );

        $this->assertEquals(201, $response->status());

        DB::rollBack();
    }

    public function testSaveInvalidFields(): void 
    {
        DB::beginTransaction();

        $response = $this->call('POST', 
            '/user', 
            [
                'name' => 'Luan Andrade',
                'cpf' => '12312312312',
                'email' => 'teste@teste.com',
                'password' => 'luan@teste',
                'money' => '400.00',
                'type' => ''
            ]
        );

        $this->assertEquals(422, $response->status());

        $response = $this->call('POST', 
            '/user', 
            [
                'name' => 'Luan Andrade'
            ]
        );

        $this->assertEquals(422, $response->status());

        $response = $this->call('POST', 
            '/user', 
            []
        );

        $this->assertEquals(422, $response->status());

        DB::rollBack();
    }
}
