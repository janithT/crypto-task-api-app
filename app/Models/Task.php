<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use SoftDeletes, HasFactory;
    
    protected $fillable = [
        'taskkey',
        'title',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'string', 
    ];

    protected $appends = ['formatted_created_at'];

    public function getFormattedCreatedAtAttribute(): string
    {
        return date('Y F d', strtotime($this->created_at));
    }

    
}
