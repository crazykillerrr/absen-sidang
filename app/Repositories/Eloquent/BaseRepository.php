<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginate($perPage = 15)
    {
        return $this->model->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = $this->find($id);
        return $record->delete();
    }

    public function findWith($id, array $relations)
    {
        return $this->model->with($relations)->findOrFail($id);
    }

    public function allWith(array $relations)
    {
        return $this->model->with($relations)->get();
    }
}
