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

            $payer = $this->user->with(['consumer', 'seller'])
                ->join('consumer', 'users.id', '=', 'consumer.user_id')
                ->join('seller', 'users.id', '=', 'seller.user_id')
                ->where('consumer.username', '=', $data['payer'])
                ->orWhere('seller.username', '=', $data['payer'])->get();

            $receiver = $this->user->with(['consumer', 'seller'])
                ->join('consumer', 'users.id', '=', 'consumer.user_id')
                ->join('seller', 'users.id', '=', 'seller.user_id')
                ->where('consumer.username', '=', $data['receiver'])
                ->orWhere('seller.username', '=', $data['receiver'])->get();

            if ($payer == null) {
                return response()->json(
                    Msg::getError("Pagador nao encontrado"),
                    404
                );
            }

            if ($receiver == null) {
                return response()->json(
                    Msg::getError("Recebedor nao encontrado"),
                    404
                );
            }

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

    public function withdraw(Request $request)
    {
        $this->validate($request, [
            'user' => 'required',
            'amount' => 'required'
        ]);

        try {
            $data = $request->all();

            $user = $this->user->with(['consumer', 'seller'])
                ->join('consumer', 'users.id', '=', 'consumer.user_id')
                ->join('seller', 'users.id', '=', 'seller.user_id')
                ->where('consumer.username', '=', $data['user'])
                ->orWhere('seller.username', '=', $data['user'])->get();

            if ($user == null) {
                return response()->json(
                    Msg::getError("Usuário nao encontrado"),
                    404
                );
            }

            if ($user->money <= 0 || $user->money > floatval($data['amount'])) {

                return response()->json(
                    Msg::getError("Voce nao tem saldo suficiente para saque"),
                    401
                );
            }

            $user->update(['money' => floatval($user->money) - floatval($data['amount'])]);

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

    public function deposit(Request $request)
    {
        $this->validate($request, [
            'user' => 'required',
            'amount' => 'required'
        ]);
        
        try {
            $data = $request->all();

            $user =  $user = $this->user->with(['consumer', 'seller'])
                ->join('consumer', 'users.id', '=', 'consumer.user_id')
                ->join('seller', 'users.id', '=', 'seller.user_id')
                ->where('consumer.username', '=', $data['user'])
                ->orWhere('seller.username', '=', $data['user'])->get();

            if ($user == null) {
                return response()->json(
                    Msg::getError("Usuário nao encontrado"),
                    404
                );
            }

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
