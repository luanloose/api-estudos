<?php

namespace App\Http\Controllers;

use App\Services\Contracts\TransferServiceContract;
use App\Services\Contracts\UserServiceContract;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;
use Illuminate\Http\Request;
use App\Rules\Authorization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransferController extends Controller
{
    private $transferService;

    public function __construct(TransferServiceContract $transferServiceContract, UserServiceContract $userServiceContract)
    {
        $this->transferService = $transferServiceContract;
        $this->userService = $userServiceContract;
    }

    public function index(): JsonResponse
    {
        try {
            return response()->json($this->transferService->all());
        } catch (Exception | Throwable $e) {
            return response()->json(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function transfer(Request $request): JsonResponse
    {
        DB::beginTransaction();

        $this->validate($request, [
            'payer_id' => 'required',
            'payee_id' => 'required',
            'value' => 'required'
        ]);

        try {
            $value = floatval($request->value);
            
            $payer = $this->userService->find($request->payer_id);

            $payee = $this->userService->find($request->payee_id);

            if (empty($payer) || empty($payee)) {

                $response = [];

                if (empty($payer)) {
                    array_push($response, "payer");
                }

                if (empty($payee)) {
                    array_push($response, "payee");
                }

                return response()->json(
                    ['error' => implode(', ', $response).' not found'],
                    404
                );
            }

            if (!$authorize = Authorization::authorize()) {
                return response()->json(
                    ['error' => 'Pagamento não autorizado'],
                    401
                );
            }
            if (!$authorize = Authorization::wallet($this->userService, $request->payer_id, $value)) {

                return response()->json(
                    ['error' => 'Saldo insuficiente'],
                    422
                );
            }
            
            $this->userService->balanceDecrement($payer['id'], $value);
            $this->userService->balanceIncrement($payee['id'], $value);

            if($saved = $this->transferService->save($request->all())) {

                if ($notify = Authorization::notify()) {
                    DB::commit();
                    return response()->json( [ 'status' => 'Success' ], JsonResponse::HTTP_CREATED);
                } else {
                    DB::rollBack();
                    return response()->json(
                        [
                            'error' => 'A plataforma esta a indisponivel, tente novamente mais tarde'
                        ],
                        JsonResponse::HTTP_BAD_REQUEST
                    );
                }                
            }

            DB::rollBack();
            return response()->json(
                [
                    'error' => 'Ocorreu um erro durante o pagamento'
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (Exception | Throwable $e) {
            DB::rollBack();
            return response()->json(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            return response()->json($this->transferService->find($id));
        } catch (Exception | Throwable $e) {
            return response()->json(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function refund(int $id): JsonResponse 
    {
        DB::beginTransaction();

        try {
            $transaction = $this->transferService->find($id);

            if ($transaction == null) {
                return response()->json(
                    ['error' => 'transaction not found'],
                    404
                );
            }

            $value = floatval($transaction['value']);
            $payer = $this->userService->find($transaction['payer_id']);
            $payee = $this->userService->find($transaction['payee_id']);

            if (empty($payer) || empty($payee)) {

                $response = [];

                if (empty($payer)) {
                    array_push($response, "payer");
                }

                if (empty($payee)) {
                    array_push($response, "payee");
                }

                return response()->json(
                    ['error' => implode(', ', $response).' not found'],
                    404
                );
            }

            if (!$authorize = Authorization::authorize()) {
                return response()->json(
                    ['error' => 'Pagamento não autorizado'],
                    401
                );
            }

            if (!$authorize = Authorization::wallet($this->userService, $payer['id'], $value)) {
                return response()->json(
                    ['error' => 'Saldo insuficiente para realizar o estorno'],
                    404
                );
            }

            $this->userService->balanceIncrement($payer['id'], $value);
            $this->userService->balanceDecrement($payee['id'], $value);

            if ($updated = $this->transferService->update(['status' => 'refunded'], $id)) {

                DB::commit();
                return response()->json(
                    $updated,
                    JsonResponse::HTTP_CREATED
                );
            }

            DB::rollBack();
            return response()->json(
                [
                    'error' => 'Ocorreu um erro no estorno'
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (Exception | Throwable $e) {
            DB::rollBack();
            return response()->json(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            return response()->json($this->transferService->delete($id));
        } catch (Exception | Throwable $e) {
            return response()->json(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
