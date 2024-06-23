<?php

namespace Modules\ActivityHistory\Entities;

use App\Models\User;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityHistory extends Model
{
    use HasFactory, HasCompany;

    const MESSAGE_EXTENDED_LEAD = "Срок продлен из-за: ";
    const MESSAGE_CREATED_LEAD = "создан новый лид";
    const MESSAGE_UPDATED_LEAD = "лид изменился";
    const MESSAGE_CREATED_ORDER = " создан новый заказ";
    const MESSAGE_ADD_PAYMENT = "создал новый лид";
    const MESSAGE_CREATED_NOTE = "комментарий оставлен";
    const LOGIN_MODULE_NAME = 'login extension';
    protected $fillable = [
        'info',
        'module_name',
        'module_id',
        'ip',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(static function (self $model) {
            if (company()) {
                $model->company_id = company()->id;
            }

            if (auth()->check()) {
                $model->user_id = auth()->id();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

//    protected static function newFactory()
//    {
//        return ActivityHistoryFactory::new();
//    }
}
