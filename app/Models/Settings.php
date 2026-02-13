<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Get a setting value by key (cached for 1 hour)
     */
    public static function get($key, $default = null)
    {
        return cache()->remember("settings.{$key}", 3600, function() use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key (clears cache)
     */
    public static function set($key, $value, $type = 'text')
    {
        $result = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
        
        // Clear cache when setting is updated
        cache()->forget("settings.{$key}");
        
        return $result;
    }
}
