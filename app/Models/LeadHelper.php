<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadHelper extends Model
{
    use HasFactory;

    protected $fillable = [
        'field',
        'time',
        'company_token',
        'from_id',
        'from_username',
        'media_id',
        'media_product_type',
        'text_id',
        'text_parent_id',
        'text',
        'comment_id',
        'recipient_id',
        'message_mid',
        'reaction',
        'emoji',
        'previous_owner_app_id',
        'new_owner_app_id',
        'metadata',
        'title',
        'payload',
        'ref',
        'source',
        'type',
        'impressions',
        'reach',
        'taps_forward',
        'taps_back',
        'exits',
        'replies',
    ];
}
