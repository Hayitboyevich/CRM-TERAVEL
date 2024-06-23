<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemaSeat extends Model
{
    use HasFactory;

    protected $table = 'schema_seats';

    protected $fillable = ['schema_id', 'row', 'cell', 'index', 'application_id', 'company_id'];
}
