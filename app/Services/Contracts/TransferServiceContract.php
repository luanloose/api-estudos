<?php

namespace App\Services\Contracts;

interface TransferServiceContract
{
    public function all(bool $paginated = true): array;
    public function save(array $data): array;
    public function find(int $id): array;
    public function update(array $data, int $id): array;
    public function delete(int $id): bool;
}
