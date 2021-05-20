<?php

namespace App\Http\Controllers;

use App\Services\Contracts\UserServiceContract;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserServiceContract $userServiceContract)
    {
        $this->userService = $userServiceContract;
    }

    public function index(): JsonResponse
    {
        try {
            return response()->json($this->userService->all());
        } catch (Exception | Throwable $e) {
            return response()->json(
            ['error' => $e->getMessage()],
            JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'cpf' => 'required|unique:users',
            'money' => 'required',
            'password' => 'required',
            'type' => 'required'
        ]);

        if ($request->type != 'seller' && $request->type != 'consumer') {
            return response()->json(
                [
                    'error' => 'Invalid type',
                    'mensage' => 'Valid types [consumer, seller]'
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        try {
            if($saved = $this->userService->save($request->all())) {
                return response()->json(
                    $saved,
                    JsonResponse::HTTP_CREATED
                );
            }

            return response()->json(
                [
                    'error' => 'Error on save',
                    'mensage' => $this->userService->errors
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (Exception | Throwable $e) {
            return response()->json(
                ['erro' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }

    public function show(int $id): JsonResponse
    {
        try {
            return response()->json($this->userService->find($id));
        } catch (Exception | Throwable $e) {
            return response()->json(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function update(Request $request, int $id ): JsonResponse 
    {
        try {
            $saved = $this->userService->update(
                $request->all(),
                $id
            );

            if($saved) {
                return response()->json(
                    $saved,
                    JsonResponse::HTTP_CREATED
                );
            }

            return response()->json(
                [
                    'error' => 'Error on update',
                    'mensage' => $this->userService->errors
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (Exception | Throwable $e) {
            return response()->json(
                ['erro' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            return response()->json($this->userService->delete($id),200);
        } catch (Exception | Throwable $e) {
            return response()->json(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
