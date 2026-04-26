<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'app_name',
    'direct_wa_number',
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
])]
class AppSetting extends Model
{
}
