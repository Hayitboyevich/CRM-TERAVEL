<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTourPackage extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'order_id', 'tour_package_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
