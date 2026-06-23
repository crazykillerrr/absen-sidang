<?php

namespace App\Services;

use App\Repositories\Contracts\QrCodeRepositoryInterface;
use SimpleSoftwareIO\QrCode\Facades\QrCode as SimpleQrCode;

class QrCodeService
{
    protected $repository;

    public function __construct(QrCodeRepositoryInterface $repository)
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

    /**
     * Menghasilkan tag SVG QR Code yang mengarah ke URL absensi publik
     *
     * @param string $kode Kode QR Code lokasi
     * @return string Data SVG QR Code
     */
    public function generateQrCodeSvg(string $kode): string
    {
        $url = url('/absensi?qrcode=' . urlencode($kode));
        
        return SimpleQrCode::size(250)
            ->margin(1)
            ->color(11, 42, 73) // Curated premium dark slate
            ->backgroundColor(255, 255, 255)
            ->generate($url);
    }
}
