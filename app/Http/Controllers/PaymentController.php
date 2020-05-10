<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Auth\Middleware\Authorize;
use App\Authorization;

class PaymentController extends Controller
{
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function pay(Request $request)
    {
        //mudar no banco para float e retirar todos casts

        $this->validate($request, [
            'payer' => 'required',
            'receiver' => 'required',
            'amount' => 'required'

        ]);

        $data = $request->all();
        $amount = floatval($data['amount']);
        $payer = $this->user->find($data['payer']);


        if (!$authorize = Authorization::wallet(floatval($payer->money),$amount)) {

            return response()
                ->json([
                    'Erro' => [
                        'message' => 'Saldo insuficiente'
                    ]
                ], 401);
        }

        if (!$authorize = Authorization::authorize($amount)) {

            return response()
                ->json([
                    'Erro' => [
                        'message' => 'Valores maiores ou iguais a 100 não são permitidos'
                    ]
                ], 401);
        }

        $receiver = $this->user->find($data['receiver']);

        $payer->update(['money' => floatval($payer->money) - $amount]);

        $receiver->update(['money' => floatval($receiver->money) + $amount]);


        return response()
            ->json([
                'data' => [
                    'message' => 'Transferencia realizada com sucesso'
                ]
            ], 200);
    }

    public function withdraw($user)
    {
        $user = $this->user->find($user);

        if ($user->money <= 0) {

            return response()
                ->json([
                    'data' => [
                        'message' => 'Voce nao tem saldo'
                    ]
                ], 401);
        }

        $user->update(['money' => 0]);

        return response()
            ->json([
                'data' => [
                    'message' => 'Saque realizado com sucesso!'
                ]
            ], 200);
    }

    public function deposit($user,Request $request)
    {
        $data = $request->all();

        $user = $this->user->find($user);

        $user->update(['money' => floatval($user->money) + floatval($data['amount'])]);

        return response()
            ->json([
                'data' => [
                    'message' => 'Deposito realizado com sucesso!'
                ]
            ], 200);
    }
}
