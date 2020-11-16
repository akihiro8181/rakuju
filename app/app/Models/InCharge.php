<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InCharge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'teacher_id', 'classwork_id',
    ];

    /**
     * 授業情報を取得 
     */
    public function classwork()
    {
        return $this->belongsTo('App\Models\Classwork');
    }

    /**
     * 授業の課題を取得 
     */
    public function classwork_tasks()
    {
        return $this->hasMany('App\Models\ClassworkTask');
    }

    /**
     * 授業の課題を取得 
     */
    public function teacher()
    {
        return $this->belongsTo('App\Models\User', 'teacher_id');
    }

    /**
     * 授業の課題を取得 
     */
    public function students()
    {
        return $this->belongsToMany('App\Models\User', 'attendances');
    }

    /**
     * 授業の課題を取得 
     */
    public function tutors()
    {
        return $this->belongsToMany('App\Models\User', 'tutors');
    }
}
