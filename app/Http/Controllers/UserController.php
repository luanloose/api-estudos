<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;


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

            return response()->json([
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            $message = 'Erro';
            return response()->json($e->getMessage(), 401);
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

            $user = $this->user->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'cpf' => $data['cpf'],
                'phone' => $data['phone'],
                'money' => $data['money'],
                'password' => $data['password']
            ]);


            if ($request->has('consumer')) {
                $user->consumer()->create($data['consumer']);
            }

            if ($request->has('seller')) {

                $user->seller()->create($data['seller']);
            }

            return response()->json([
                'data' => [
                    'msg' => 'Usuário cadastrado com sucesso!'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = 'Erro';
            return response()->json($e->getMessage(), 401);
        }
    }

    public function update($user, Request $request)
    {
        $data = $request->all();

        try {

            $user = $this->user->findOrFail($user);
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'cpf' => $data['cpf'],
                'phone' => $data['phone'],
                'money' => $data['money'],
                'password' => $data['password']
            ]);

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

            return response()->json([
                'data' => [
                    'msg' => 'Usuário atualizado com sucesso!'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = 'Erro no update';
            return response()->json($e->getMessage(), 500);
        }
    }


    public function destroy($user)
    {
        $user = $this->user->find($user);

        $user->delete();

        return response()
            ->json([
                'data' => [
                    'message' => 'Usuario foi removido com sucesso!'
                ]
            ]);
    }
}
