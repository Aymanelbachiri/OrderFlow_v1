<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    protected $fillable = [
        'mailer',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
    ];

    protected $casts = [
        'port' => 'integer',
    ];

    /**
     * Get the first SMTP setting record
     */
    public static function getFirst(): ?self
    {
        return static::first();
    }

    /**
     * Create or update the single SMTP setting record
     */
    public static function updateOrCreate(array $data): self
    {
        $setting = static::first();
        
        if ($setting) {
            $setting->update($data);
            return $setting;
        }
        
        return static::create($data);
    }
}
