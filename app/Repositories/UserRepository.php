<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Return all Users
     * @return array
     */
    public function index(): array
    {
        return User::all()->toArray();
    }

    /**
     * Create a new User
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Find a User by id
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Find a User by name
     * @param string $name
     * @return User|null
     */
    public function findByName(string $name): ?User
    {
        return User::where('name', $name)->first();
    }

    /**
     * Find a User by email
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Update a User
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return User::find($id)->update($data);
    }

    /**
     * Delete a User
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return User::destroy($id);
    }
}