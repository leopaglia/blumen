<?php

namespace App\Repositories\Base;
use Illuminate\Database\Eloquent\Model;


/**
 * Class BaseRepository
 * @package App\Repositories\Base
 */
interface BaseRepositoryInterface
{
    /**
     * Fetch every model
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll();

    /**
     * Fetch a model by id
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findById($id);

    /**
     * Find one entity by key value
     * Usage: findOneBy('id', '=', 123)
     *        findOneBy(['id', 123])
     *        findOneBy([['age', '>', 18], ['name', 'john']])
     *
     * @param string | array $key
     * @param string $operator
     * @param $value
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOneBy($key, $operator = null, $value = null);

    /**
     * Find many entities by key value
     *
     * Usage: findBy('id', '=', 123)
     *        findBy(['id', 123])
     *        findBy([['age', '>', 18], ['name', 'john']])
     *
     * @param string | array $key
     * @param string $operator
     * @param $value
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findBy($key, $operator = null, $value = null);

    /**
     * Update a model
     *
     * @param $id
     * @param array $attributes
     * @return bool
     */
    public function update($id, array $attributes = []);

    /**
     * Save a model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function save(Model $model);

    /**
     * Delete a model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function delete(Model $model);

    /**
     * Delete model/s by id
     *
     * @param int | array $id
     */
    public function deleteById($id);

    /**
     * Create a model and save it
     * Model fields are filled with $attributes that correspond to the model $fillable fields
     * Return the model if successful, throw an exception on failure
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function create(array $attributes = []);
}