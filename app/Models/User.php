<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_position',
        'phone_number',
        'address_id',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function cart():HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function employee():HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function address():BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function get_id() {
        return $this->attributes['id'];
    }

    public function get_name() {
        return $this->attributes['name'];
    }

    public function get_email() {
        return $this->attributes['email'];
    }

    public function get_role() {
        return $this->attributes['role'];
    }

    public function get_company_position() {
        return $this->attributes['company_position'];
    }

    public function get_phone_number() {
        return $this->attributes['phone_number'];
    }

    public function get_address_id() {
        return $this->attributes['address_id'];
    }

    public function get_company_id() {
        return $this->attributes['company_id'];
    }

    public static function allowedRoles(): array
    {
        return ['admin', 'person'];
    }

}
