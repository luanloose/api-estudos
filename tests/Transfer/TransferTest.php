<?php

namespace Tests\Feature\Transfer;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransferTest extends TestCase
{
    public function testGetAllTransfers()
    {
        $response = $this->call('GET', '/transfer');

        $this->assertEquals(200, $response->status());
    }

    public function testValidTransfer(): void 
    {
        DB::beginTransaction();

        $payer = User::query()->create([
            'name' => 'Luan',
            'cpf' => '12312312312',
            'email' => '1111@11111.com.br',
            'password' => 'luan123',
            'money' => '300',
            'type' => 'consumer'
        ]);

        $payee = User::query()->create([
            'name' => 'Ana paula',
            'cpf' => '987654321',
            'email' => '222@22222.com.br',
            'password' => 'ana123',
            'money' => '300',
            'type' => 'consumer'
        ]);

        $response = $this->call('POST', 
            '/transfer', 
            [
                'payer_id' => $payer->id,
                'payee_id' => $payee->id,
                'value' => 100.00,
            ]
        );

        $this->assertEquals(201, $response->status());

        $this->seeInDatabase('users', [
            'id' => $payer->id,
            'money' => 200
        ]);

        $this->seeInDatabase('users', [
            'id' => $payee->id,
            'money' => 400
        ]);

        DB::rollBack();
    }

    public function testInvalidFields(): void 
    {
        DB::beginTransaction();

        $response = $this->call('POST', 
            '/transfer', 
            [
                'payer_id' => 1
            ]
        );

        $this->assertEquals(422, $response->status());

        $response = $this->call('POST', 
            '/transfer', 
            [
                'payer_id' => 1,
                'payee_id' => 2,
            ]
        );

        $this->assertEquals(422, $response->status());

        DB::rollBack();
    }

    public function testInvalidPayee(): void 
    {
        DB::beginTransaction();

        $response = $this->call('POST', 
            '/transfer', 
            [
                'payer_id' => 1,
                'payee_id' => 999999999,
                'value' => 100.00,
            ]
        );

        $this->assertEquals(404, $response->status());

        DB::rollBack();
    }

    public function testInvalidPayer(): void 
    {
        DB::beginTransaction();

        $response = $this->call('POST', 
            '/transfer', 
            [
                'payer_id' => 999999999,
                'payee_id' => 1,
                'value' => 100.00,
            ]
        );

        $this->assertEquals(404, $response->status());
        DB::rollBack();
    }


    public function testSellerTransfer(): void 
    {
        DB::beginTransaction();

        $payer = User::query()->create([
            'name' => 'Luan',
            'cpf' => '12312312312',
            'email' => '1111@11111.com.br',
            'password' => 'luan123',
            'money' => '300',
            'type' => 'seller'
        ]);

        $payee = User::query()->create([
            'name' => 'Ana paula',
            'cpf' => '987654321',
            'email' => '222@22222.com.br',
            'password' => 'ana123',
            'money' => '300',
            'type' => 'consumer'
        ]);

        $response = $this->call('POST', 
            '/transfer', 
            [
                'payer_id' => $payer->id,
                'payee_id' => $payee->id,
                'value' => 100.00,
            ]
        );

        $this->assertEquals(403, $response->status());

        DB::rollBack();
    }
}