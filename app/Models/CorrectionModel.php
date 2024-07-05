<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectionModel extends Model
{
    use HasFactory;
    protected $table = 'memid_pbno_correction';
    protected $fillable = [
        'id',
        'memid',
        'pbno'
    ];
}
