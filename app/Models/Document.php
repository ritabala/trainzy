<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToGym;

class Document extends Model
{
    use BelongsToGym;

    protected $fillable = [
        'name',
        'type',
        'file_type',
        'user_id',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 