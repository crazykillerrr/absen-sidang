<?php

namespace App\Services;

use App\Repositories\Contracts\PerkaraRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PerkaraService
{
    protected $repository;

    public function __construct(PerkaraRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function allWith(array $relations)
    {
        return $this->repository->allWith($relations);
    }

    public function paginate(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function findWith(int $id, array $relations)
    {
        return $this->repository->findWith($id, $relations);
    }

    /**
     * Buat Perkara
     */
    public function createPerkara(array $data)
    {
        return $this->repository->create($data);
    }

    /**
     * Update Perkara
     */
    public function updatePerkara(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        // Hubungan di pivot table menggunakan onDelete('cascade'), jadi relasi di database akan otomatis terhapus
        // dan karena model Perkara menggunakan SoftDeletes, data perkara disembunyikan.
        return $this->repository->delete($id);
    }
}
