<?php

namespace App\Services;

use App\Repositories\Contracts\PerkaraRepositoryInterface;
use App\Models\MajelisHakim;
use App\Models\PenugasanPp;
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
     * Buat Perkara beserta Majelis Hakim dan Panitera Pengganti dalam satu transaksi
     */
    public function createPerkara(array $data, int $ketuaHakimId, array $anggotaHakimIds, int $ppId)
    {
        return DB::transaction(function () use ($data, $ketuaHakimId, $anggotaHakimIds, $ppId) {
            // 1. Buat Perkara
            $perkara = $this->repository->create($data);

            // 2. Simpan Ketua Majelis
            MajelisHakim::create([
                'perkara_id' => $perkara->id,
                'hakim_id' => $ketuaHakimId,
                'jabatan' => 'Ketua Majelis',
            ]);

            // 3. Simpan Hakim Anggota
            foreach ($anggotaHakimIds as $hakimId) {
                if ($hakimId != $ketuaHakimId) { // Hindari duplikasi jika tidak sengaja terpilih sama
                    MajelisHakim::create([
                        'perkara_id' => $perkara->id,
                        'hakim_id' => $hakimId,
                        'jabatan' => 'Hakim Anggota',
                    ]);
                }
            }

            // 4. Simpan Penugasan Panitera Pengganti
            PenugasanPp::create([
                'perkara_id' => $perkara->id,
                'panitera_pengganti_id' => $ppId,
            ]);

            return $perkara;
        });
    }

    /**
     * Update Perkara beserta Majelis Hakim dan Panitera Pengganti dalam satu transaksi
     */
    public function updatePerkara(int $id, array $data, int $ketuaHakimId, array $anggotaHakimIds, int $ppId)
    {
        return DB::transaction(function () use ($id, $data, $ketuaHakimId, $anggotaHakimIds, $ppId) {
            // 1. Update Perkara
            $perkara = $this->repository->update($id, $data);

            // 2. Sync Majelis Hakim (Hapus yang lama, simpan baru)
            MajelisHakim::where('perkara_id', $perkara->id)->delete();
            
            MajelisHakim::create([
                'perkara_id' => $perkara->id,
                'hakim_id' => $ketuaHakimId,
                'jabatan' => 'Ketua Majelis',
            ]);

            foreach ($anggotaHakimIds as $hakimId) {
                if ($hakimId != $ketuaHakimId) {
                    MajelisHakim::create([
                        'perkara_id' => $perkara->id,
                        'hakim_id' => $hakimId,
                        'jabatan' => 'Hakim Anggota',
                    ]);
                }
            }

            // 3. Sync PP (Hapus lama, simpan baru)
            PenugasanPp::where('perkara_id', $perkara->id)->delete();
            
            PenugasanPp::create([
                'perkara_id' => $perkara->id,
                'panitera_pengganti_id' => $ppId,
            ]);

            return $perkara;
        });
    }

    public function delete(int $id)
    {
        // Hubungan di pivot table menggunakan onDelete('cascade'), jadi relasi di database akan otomatis terhapus
        // dan karena model Perkara menggunakan SoftDeletes, data perkara disembunyikan.
        return $this->repository->delete($id);
    }
}
