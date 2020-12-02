<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','workspace_url'
    ];

    /**
     * 授業を取得
     */
    public function classworks()
    {
        return $this->hasMany('App\Models\Classwork');
    }
}
