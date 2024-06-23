<?php

namespace App\Services;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class DataCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, $key, $value, $attributes)
    {
        return !empty($value) ? \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d') : null;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        try {
            $date = \Carbon\Carbon::createFromFormat('Y-m-d', $value);
            return $date->format('Y-m-d'); // returns a string in 'Y-m-d' format
        } catch (\Exception $e) {
            Log::error('Error in DataCast.php: ' . $e->getMessage());
        }
    }

}
