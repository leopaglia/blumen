<?php

namespace App\Models\Traits;

/**
 * Trait for Eloquent Models
 * Removes getAttribute methods to lighten processing and cut times.
 * Do not use this if you need the $appends / getXXXAttribute functionality.
 *
 * Class LightModel
 * @package App\Models\Traits
 */
trait LightModel {
    /**
     * Extension to model, removed casts and mutated attributes.
     *
     * @return array
     */
    public function attributesToArray() {
        $attributes = $this->getArrayableAttributes();

        // If an attribute is a date, we will cast it to a string after converting it
        // to a DateTime / Carbon instance. This is so we will get some consistent
        // formatting while accessing attributes vs. arraying / JSONing a model.
        foreach ($this->getDates() as $key) {
            if (! isset($attributes[$key])) {
                continue;
            }

            $attributes[$key] = $this->serializeDate(
                $this->asDateTime($attributes[$key])
            );
        }

        // Here we will grab all of the appended, calculated attributes to this model
        // as these attributes are not really in the attributes array, but are run
        // when we need to array or JSON the model for convenience to the coder.
        foreach ($this->getArrayableAppends() as $key) {
            $attributes[$key] = $this->mutateAttributeForArray($key, null);
        }

        return $attributes;
    }



    /**
     * Get a plain attribute (not a relationship), REMOVED hasCast AND hasGetMutator.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttributeValue($key) {
        $value = $this->getAttributeFromArray($key);

        // If the attribute is listed as a date, we will convert it to a DateTime
        // instance on retrieval, which makes it quite convenient to work with
        // date fields without having to create a mutator for each property.
        if (in_array($key, $this->getDates()) && ! is_null($value)) {
            return $this->asDateTime($value);
        }

        return $value;
    }

    /**
     * Get an attribute from the model, REMOVED hasGetMutator.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key) {
        if (array_key_exists($key, $this->attributes)) {
            return $this->getAttributeValue($key);
        }

        return $this->getRelationValue($key);
    }
}