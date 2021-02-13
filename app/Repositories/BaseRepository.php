<?php


namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{
    public static int $perPage = 6;
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @param array $attributes
     * @return array
     */
    public function all(array $attributes)
    {
        $query = $this->model;
        foreach ($attributes as $key => $val) {
            $query = $query->where($key, $val);
        }
        return [
            'data' => $query->take(self::$perPage)->get()
        ];
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model
    {
        if ($this->model->id !== $id) {
            $this->model = $this->model->find($id);
        }
        return $this->model;
    }

    /**
     * @param $id
     * @return \Exception|bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        return $this->model->delete();
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return bool
     */
    public function save()
    {
        return $this->model->save();
    }

    /**
     * @param array $data
     */
    public function fill(array $data)
    {
        $this->model->fill($data);
    }
}
