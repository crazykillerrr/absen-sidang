<?php

namespace App\Services;

use App\Repositories\Contracts\PaniteraPenggantiRepositoryInterface;

class PaniteraPenggantiService
{
    protected $repository;

    public function __construct(PaniteraPenggantiRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function paginate(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}
