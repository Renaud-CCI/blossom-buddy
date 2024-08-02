<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function index(): array;

    public function create(array $data): User;

    public function findById(int $id): ?User;

    public function findByName(string $name): ?User;

    public function findByEmail(string $email): ?User;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}