<?php

namespace App\Repositories\Base;

use App\Aspects\Annotations\Cacheable;

use App\Aspects\Annotations\InvalidatesCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Class BaseRepository
 * @package App\Repositories\Base
 */
abstract class BaseRepository
{
    /**
     * Fetch every model
     *
     * @Cacheable
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll()
    {
        return $this->baseQuery()->get();
    }

    /**
     * Fetch a model by id
     *
     * @Cacheable
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findById($id)
    {
        return $this->baseQuery()->where('id', $id)->first();
    }

    /**
     * Find one entity by key value
     * Usage: findOneBy('id', 123)
     *        findOneBy('id', 123, ['Posts', 'Devices'])
     *        findOneBy([['age', '>', 18], ['name', 'john']])
     *        findOneBy([['age', '>', 18], ['name', 'john']], null, ['Posts', 'Devices'])
     *
     * @Cacheable
     * @param string | array $key
     * @param $value
     * @param array $with
     * @return Model
     */
    public function findOneBy($key, $value, $with = [])
    {
        return $this->baseQuery()->where($key, $value)->with($with)->first();
    }

    /**
     * Find many entities by key value
     *
     * Usage: findBy('id', 123)
     *        findBy('id', 123, ['Posts', 'Devices'])
     *        findBy([['age', '>', 18], ['name', 'john']])
     *        findBy([['age', '>', 18], ['name', 'john']], null, ['Posts', 'Devices'])
     *
     * @Cacheable
     * @param string | array $key
     * @param $value
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findBy($key, $value, $with = [])
    {
        return $this->baseQuery()->where($key, $value)->with($with)->get();
    }

    /**
     * Update a model
     *
     * @InvalidatesCache
     * @param $id
     * @param array $attributes
     * @return bool
     */
    public function update($id, array $attributes = [])
    {
        return $this->findById($id)->update($attributes);
    }

    /**
     * Save a model
     *
     * @InvalidatesCache
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function save(Model $model)
    {
        return $model->save();
    }

    /**
     * Delete a model
     *
     * @InvalidatesCache
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function delete(Model $model)
    {
        return $model->delete();
    }

    /**
     * Delete model/s by id
     *
     * @InvalidatesCache
     * @param int | array $id
     * @return bool
     */
    public function deleteById($id)
    {
        if(!is_array($id)) $id = [$id];
        return $this->baseQuery()->whereIn('id', $id)->delete();
    }

    /**
     * Create a model and save it
     * Model fields are filled with $attributes that correspond to the model $fillable fields
     * Return the model if successful, throw an exception on failure
     *
     * @InvalidatesCache
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function create(array $attributes = [])
    {
        $class = $this->getModelNamespace();
        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = new $class($attributes);
        $res = $model->save();
        if ($res !== true) throw new \Exception('Error creating model.');
        return $model;
    }

    /**
     * Gets name of the model associated to this repository by convention
     * Can be overriden
     *
     * @return string
     */
    protected function getModelName()
    {
        $fullyQualifiedClassName = substr(static::class, 0, -10); // \App\Repositories\{name}Repository => \App\Repositories\{name}
        $nameArray = explode('\\', $fullyQualifiedClassName); // [App, Repositories, {name}]
        return array_pop($nameArray); // {name}
    }

    /**
     * Gets fully qualified namespace of the model associated to this repository by convention
     *
     * @return string
     */
    protected function getModelNamespace()
    {
        return 'App\\Models\\' . $this->getModelName();
    }

    /**
     * Base query to be used in every other query performed by this repository
     * Meant to be overriden
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function baseQuery()
    {
        $model = $this->getModelNamespace();
        return App::make($model);
    }

    /**
     * Returns the model associated with this repository
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getModel()
    {
        return App::make($this->getModelNamespace());
    }
}