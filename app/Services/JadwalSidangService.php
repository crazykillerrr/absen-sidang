<?php

namespace App\Services;

use App\Repositories\Contracts\JadwalSidangRepositoryInterface;

class JadwalSidangService
{
    protected $repository;

    public function __construct(JadwalSidangRepositoryInterface $repository)
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

    /**
     * Dapatkan jadwal sidang hari ini beserta detail perkara dan ruang sidang
     */
    public function getTodaySchedules()
    {
        return $this->repository->getTodaySchedules();
    }

    /**
     * Dapatkan jadwal sidang aktif hari ini (untuk dropdown absensi publik)
     */
    public function getActiveSchedulesForToday()
    {
        return $this->repository->getActiveSchedulesForToday();
    }
}
