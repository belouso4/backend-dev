<?php
namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\IBase;

abstract class BaseRepository implements IBase {

    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    public function all()
    {
        return $this->model->get();
    }

    public function find($id)
    {
        $result = $this->model->findOrFail($id);
        return $result;
    }

    public function findWhere($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    public function findBySlugOrFail($column, $value)
    {
        return $this->model->withTrashed()->where($column, $value)->firstOrFail();
    }

    public function create(array $data)
    {
        $result = $this->model->create($data);
        return $result;
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete($id) {
        return $this->model
            ->destroy($id);
    }

    protected function getModelClass()
    {
//        if( !method_exists($this, 'model'))
//        {
//            throw new ModelNotDefined();
//        }

        return app()->make($this->model());

    }
}
