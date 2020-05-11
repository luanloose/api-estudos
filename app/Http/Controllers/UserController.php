<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Api\ApiMessages as Msg;

class UserController extends Controller
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

    public function index()
    {
        return $this->user->with(['consumer', 'seller'])->paginate(10);
    }

    public function show($user)
    {

        try {

            $user = $this->user->with(['consumer', 'seller'])
                ->join('consumer', 'users.id', '=', 'consumer.user_id')
                ->join('seller', 'users.id', '=', 'seller.user_id')
                ->where('name', 'like', "%{$user}%")
                ->orWhere('consumer.username', 'like', "%{$user}%")
                ->orWhere('seller.username', 'like', "%{$user}%")->get();


            if ($user) {
                return response()->json(
                    ['Usuario' => $user],
                    200
                );
            }

            return response()->json(
                Msg::getError("Usuário nao encontrado"),
                404
            );
        } catch (\Exception $e) {
            return response()->json(
                Msg::getError("Ocorreu um erro na busca, contate o administrador"),
                500
            );
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'cpf' => 'required',
            'phone' => 'required',
            'money' => 'required',
            'password' => 'required'

        ]);

        $data = $request->all();

        try {

            $user = $this->user->create($data['user']);


            if ($request->has('consumer')) {
                $user->consumer()->create($data['consumer']);
            }

            if ($request->has('seller')) {

                $user->seller()->create($data['seller']);
            }

            return response()->json(
                Msg::getSucess("Usuário cadastrado com sucesso!"),
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                Msg::getError("Ocorreu um erro no cadastro, contate o administrador"),
                500
            );
        }
    }

    public function update($user, Request $request)
    {
        $data = $request->all();

        try {

            $user = $this->user->findOrFail($user);
            $user->update($data['user']);

            if ($request->has('seller')) {

                if ($user->seller == null) {
                    $user->seller()->create($data['seller']);
                } else {
                    $user->seller()->update($data['seller']);
                }
            }

            if ($request->has('consumer')) {
                if ($user->consumer == null) {
                    $user->consumer()->create($data['consumer']);
                } else {
                    $user->seller()->update($data['consumer']);
                }
            }

            return response()->json(
                Msg::getSucess("Usuário atualizado com sucesso!"),
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                Msg::getError("Ocorreu um erro na atualização, contate o administrador"),
                500
            );
        }
    }

    public function destroy($user)
    {
        $user = $this->user->find($user);

        $user->delete();

        return response()->json(
            Msg::getSucess("Usuario foi removido com sucesso!"),
            200
        );
    }
}
