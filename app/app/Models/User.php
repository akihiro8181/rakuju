<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'login_number', 'school_id','roll_flag'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * 受講中の授業を取得
     */
    public function attendances()
    {
        return $this->belongsToMany('App\Models\InCharge', 'attendances');
    }
    
    /**
     * 担当の授業を取得
     */
    public function in_charges()
    {
        return $this->hasMany('App\Models\InCharge', 'teacher_id');
    }
    
    /**
     * 担当の授業を取得
     */
    public function tutors()
    {
        return $this->belongsToMany('App\Models\InCharge', 'tutors');
    }

    /**
     * 所属している学校の情報を取得
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School');
    }

    /**
     * ユーザーは生徒であるかを判定
     */
    public function isStudent()
    {
        return $this->roll_flag == 'st';
    }

    /**
     * ユーザーは教員であるかを判定
     */
    public function isTeacher()
    {
        return $this->roll_flag == 'te';
    }

    /**
     * ユーザーは管理者であるかを判定
     */
    public function isAdmin()
    {
        return $this->roll_flag == 'ad';
    }
}
