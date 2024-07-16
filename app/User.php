<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ClientArticle;


class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use HasApiTokens;
    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname', 'email', 'password', 'phone', 'phone2','address', 'city', 'state','country', 'dob', 'zip', 'joining_date', 'avatar','salesman_commission','skype','facebook','wechat','whatsapp','pinterest','line','company_name','business_nature', 'business_nature_other', 'newsletter','api_user'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date:Y-m-d',
    ];

    protected $dates = ['deleted_at'];


    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function getDobAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function setJoiningDateAttribute($value)
    {
        $this->attributes['joining_date'] = empty($value) ? date('Y-m-d') : Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function getJoiningDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getFullNameAttribute()
    {
        return $this->firstname . " " . $this->lastname;
    }

    public function pricelist()
    {
        return $this->hasMany('App\CustomerItemPrice','customer_id')->withTrashed();
    }

    public function clientArticles()
    {
        return $this->hasMany(ClientArticle::class, 'client_id');
    }

    protected $appends = ['full_name'];

}
