<?php

namespace App\Models\Traits;

/**
 * Trait for Eloquent Models
 * Removes every method that brings write access to the model.
 *
 * Class ReadOnly
 * @package App\Models\Traits
 */
trait ReadOnly {

    /**
     * Don't save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = []) {
        return false;
    }

    /**
     * Don't update the model in the database.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = []) {
        return false;
    }

    /**
     * Don't delete the model from the database.
     *
     * @return bool
     */
    public function delete() {
        return false;
    }

    /**
     * Don't destroy the models for the given IDs.
     *
     * @param  array|int  $ids
     * @return int
     */
    public static function destroy($ids) {
        return 0;
    }

    static function firstOrCreate(array $arr){
        return false;
    }

    static function firstOrNew(array $arr){
        return false;
    }

    public function restore(){
        return false;
    }

    public function forceDelete(){
        return false;
    }

    /* We need to disable date mutators, because they brake toArray function on this model */
    public function getDates(){
        return array();
    }

}