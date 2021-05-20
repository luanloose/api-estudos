<?php

namespace App\Services\Applications;

use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Contracts\UserServiceContract;

class UserService implements UserServiceContract
{
    public $errors;

    /**
     * @var UserRepositoryContract
     */
    private $userRepository;

    /**
     * UserService constructor.
     * @param UserRepositoryContract $userRepositoryContract
     */
    public function __construct(UserRepositoryContract $userRepositoryContract)
    {
        $this->userRepository = $userRepositoryContract;
    }

    public function all(bool $paginated = true): array
    {
        return $this->userRepository->all($paginated);
    }

    public function save(array $data): array
    {
        return $this->userRepository->save($data);
    }

    public function find($id): array
    {
        return $this->userRepository->find($id);
    }

    public function update(array $data, int $id): array
    {
        return $this->userRepository->update($data, $id);
    }

    public function delete($id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function isSeller(int $id): bool
    {
        return $this->userRepository->isSeller($id);
    }

    public function balanceIncrement(int $userId, float $value): void
    {
        $user = $this->find($userId);

        $this->update(
            ['money' => floatval($user['money']) + $value],
            $userId
        );
    }

    public function balanceDecrement(int $userId, float $value): void
    {
        $user = $this->find($userId);

        $this->update(
            ['money' => floatval($user['money']) - $value],
            $userId
        );
    }
}
