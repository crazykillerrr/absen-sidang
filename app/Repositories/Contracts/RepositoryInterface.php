<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function all();
    public function paginate($perPage = 15);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findWith($id, array $relations);
    public function allWith(array $relations);
}
