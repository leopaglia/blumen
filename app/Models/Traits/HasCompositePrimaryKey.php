<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait for Eloquent Models
 * Allows model to have composite primary key.
 * 
 * Class HasCompositePrimaryKey
 * @package App\Models\Traits
 */
trait HasCompositePrimaryKey {
    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing() {
        return false;
    }

    /**
     * @param Builder $query
     * @return Builder
     * @throws \Exception
     */
    protected function setKeysForSaveQuery(Builder $query) {
        foreach ($this->getKeyName() as $key) {
            if ($this->$key)
                $query->where($key, '=', $this->$key);
            else
                throw new \Exception(__METHOD__ . 'Missing part of the primary key: ' . $key);
        }

        return $query;
    }
}