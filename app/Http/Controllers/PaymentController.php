<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Authorization;
use App\Api\ApiMessages as Msg;

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
        $this->validate($request, [
            'payer' => 'required',
            'receiver' => 'required',
            'amount' => 'required'

        ]);

        try {
            $data = $request->all();
            $amount = floatval($data['amount']);
            $payer = $this->user->find($data['payer']);

            if (!$authorize = Authorization::wallet(floatval($payer->money), $amount)) {

                return response()->json(
                    Msg::getError("Saldo insuficiente para realizar a transação"),
                    401
                );
            }

            if (!$authorize = Authorization::authorize($amount)) {

                return response()->json(
                    Msg::getError("Valores maiores ou iguais a 100 não são permitidos"),
                    401
                );
            }

            $receiver = $this->user->find($data['receiver']);

            $payer->update(['money' => floatval($payer->money) - $amount]);

            $receiver->update(['money' => floatval($receiver->money) + $amount]);

            return response()->json(
                Msg::getSucess("Transferencia realizada com sucesso"),
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                Msg::getError("Ocorreu um erro na busca, contate o administrador"),
                500
            );
        }
    }

    public function withdraw($user)
    {
        try {
            $user = $this->user->find($user);

            if ($user->money <= 0) {

                return response()->json(
                    Msg::getError("Voce nao tem saldo para saque"),
                    401
                );
            }

            //mudar para quantia dps
            $user->update(['money' => 0]);

            return response()->json(
                Msg::getSucess("Saque realizado com sucesso!"),
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                Msg::getError("Ocorreu um erro no saque, contate o administrador"),
                500
            );
        }
    }

    // mudar logica da rota para receber tudo no post
    public function deposit($user, Request $request)
    {
        try {
            $data = $request->all();

            $user = $this->user->find($user);

            $user->update(['money' => floatval($user->money) + floatval($data['amount'])]);

            return response()->json(
                Msg::getSucess("Deposito realizado com sucesso!"),
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                Msg::getError("Ocorreu um erro no deposito, contate o administrador"),
                500
            );
        }
    }
}
