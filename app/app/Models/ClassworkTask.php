<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassworkTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'in_charge_id', 'name', 'deadline', 'sort_num', 'content',
    ];

    protected $casts = [
        'content' => 'json',
    ];
}
