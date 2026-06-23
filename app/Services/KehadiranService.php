<?php

namespace App\Services;

use App\Repositories\Contracts\KehadiranRepositoryInterface;
use App\Services\AttendanceValidationService;
use Illuminate\Support\Facades\DB;

class KehadiranService
{
    protected $repository;
    protected $validationService;

    public function __construct(
        KehadiranRepositoryInterface $repository,
        AttendanceValidationService $validationService
    ) {
        $this->repository = $repository;
        $this->validationService = $validationService;
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

    /**
     * Catat kehadiran dan jalankan validasi kehadiran otomatis untuk memicu notifikasi
     *
     * @param array $data Data kehadiran
     * @param int $jadwalSidangId ID Jadwal Sidang terkait
     * @return mixed
     */
    public function recordAttendance(array $data, int $jadwalSidangId)
    {
        return DB::transaction(function () use ($data, $jadwalSidangId) {
            // 1. Buat catatan kehadiran
            $kehadiran = $this->repository->create($data);

            // 2. Jalankan validasi kehadiran otomatis untuk notifikasi WhatsApp
            $this->validationService->validateAndNotify($jadwalSidangId);

            return $kehadiran;
        });
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}
