<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $fillable = ['key', 'value', 'group', 'description', 'is_secret'];

    protected $casts = [
        'is_secret' => 'boolean',
    ];

    public static function getValue($key, $default = null)
    {
        $config = static::where('key', $key)->first();
        return $config ? $config->value : $default;
    }
}
