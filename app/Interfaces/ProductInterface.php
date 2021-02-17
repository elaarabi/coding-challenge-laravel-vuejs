<?php

namespace App\Interfaces;


use Illuminate\Database\Eloquent\Model;

interface ProductInterface
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public function all(array $attributes);

    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param $id
     * @return Model|null
     */
    public function find($id): ?Model;

    /**
     * @param $id
     * @return boolean
     */
    public function delete($id);

    /**
     * @return boolean
     */
    public function save();
}
