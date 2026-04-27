<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = [
        'app_name',
        'direct_wa_number',
        'address',
        'operational_hours',
        'reservation_info',
        'google_maps_embed',
        'owner_photo_path',
        'logo_path',
        'forex_referral_link',
        'ihsg_stock_referral_link',
        'wa_group_link',
        'telegram_group_link',
        'facebook_link',
        'instagram_link',
        'tiktok_link',
        'youtube_link',
    ];
}
