<?php


namespace App\Repositories\Contracts;


interface TransferRepositoryContract
{
    public function all(bool $paginated, ?int $perPage = null): array;
    public function save(array $data): array;
    public function find(int $id): array;
    public function update(array $data, int $id): array;
    public function delete(int $id): bool;
}
