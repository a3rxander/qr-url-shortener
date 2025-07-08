<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = [
        'identifier',
        'content',
        'type',
        'format',
        'size',
        'file_path'
    ];

    protected $casts = [
        'size' => 'integer',
    ];
}