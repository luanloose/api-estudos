<?php

namespace App\Services\Contracts;

interface UserServiceContract
{
    public function all(bool $paginated = true): array;
    public function save(array $data): array;
    public function find($id): array;
    public function update(array $data, int $id): array;
    public function delete($id): bool;
    public function isSeller(int $value): bool;
}
