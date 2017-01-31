<?php

namespace App\Repositories\Base;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseCacheDecorator
 * @package App\Repositories\Base
 */
abstract class BaseCacheDecorator implements BaseRepositoryInterface
{
    protected $repository;
    protected $cache;

    /**
     * BaseCacheDecorator constructor
     *
     * @param Cache $cache
     * @param BaseRepositoryInterface $repository
     */
    public function __construct(Cache $cache, BaseRepositoryInterface $repository)
    {
        $this->cache = $cache;
        $this->repository = $repository;
    }

    /**
     * Build a key to reference the cache
     *
     * @param $method
     * @param $identifier
     * @return string
     */
    private function buildKey($method, $identifier = null)
    {
        return md5($method . $identifier);
    }

    /**
     * Fetch every model
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll()
    {
        $key = $this->buildKey(__METHOD__);

        return $this->cache->tags(__CLASS__)->rememberForever($key, function() {
            return $this->repository->findAll();
        });
    }


    /**
     * Fetch a model by id
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findById($id)
    {
        $key = $this->buildKey(__METHOD__, $id);

        return $this->cache->tags(__CLASS__)->rememberForever($key, function() use($id) {
            return $this->repository->findById($id);
        });
    }

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
    public function findOneBy($key, $operator = null, $value = null)
    {
        $key = $this->buildKey(__METHOD__, $key.$operator.$value);

        return $this->cache->tags(__CLASS__)->rememberForever($key, function() use($key, $operator, $value) {
            return $this->repository->findOneBy($key, $operator, $value);
        });
    }

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
    public function findBy($key, $operator = null, $value = null)
    {
        $key = $this->buildKey(__METHOD__, $key.$operator.$value);

        return $this->cache->tags(__CLASS__)->rememberForever($key, function() use($key, $operator, $value) {
            return $this->repository->findBy($key, $operator, $value);
        });
    }

    /**
     * Save a model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function save(Model $model)
    {
        $this->cache->tags(__CLASS__)->flush();
        return $this->repository->save($model);
    }

    /**
     * Delete a model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function delete(Model $model)
    {
        $this->cache->tags(__CLASS__)->flush();
        return $this->repository->delete($model);
    }

    /**
     * Delete model/s by id
     *
     * @param int | array $id
     */
    public function deleteById($id)
    {
        $this->cache->tags(__CLASS__)->flush();
        return $this->repository->deleteById($id);
    }

    /**
     * Create a model and save it
     * Model fields are filled with $attributes that correspond to the model $fillable fields
     * Return the model if successful, throw an exception on failure
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function create(array $attributes = [])
    {
        $this->cache->tags(__CLASS__)->flush();
        return $this->repository->create($attributes);
    }
}