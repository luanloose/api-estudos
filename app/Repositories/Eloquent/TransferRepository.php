<?php

namespace App\Repositories\Eloquent;

use App\Models\Transfer;
use App\Repositories\Contracts\TransferRepositoryContract;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class TransferRepository implements TransferRepositoryContract
{
    private $model;

    public function __construct(Transfer $transfer)
    {
        $this->model = $transfer;
    }

    public function all(bool $paginated, ?int $perPage = null ): array 
    {
        if(!$paginated) {
            return $this->model->get();
        }

        return $this->model->paginate(
            $perPage ?? $this->model->getPerPage()
        )->toArray();
    }

    public function save(array $data): array
    {
        if($created = $this->model->create($data)) {
            return $this->find($created->id);
        }

        return [];
    }

    public function find(int $id): array
    {
        if($data = $this->model->find($id)) {
            return $data->toArray();
        }

        return [];
    }

    public function update(array $data, int $id): array
    {
        if($transfer = $this->model->find($id)) {
            $transfer->fill($data);
            $transfer->save();

            return $transfer->toArray();
        }

        throw new Exception('Error on update.');
    }

    public function delete(int $id): bool
    {
        if (!($dados = $this->model->find($id))) {
            throw new Exception('Register not found.');
        }

        if(!$dados->delete()) {
            throw new Exception('Error on remove register.');
        }

        return true;
    }
}
