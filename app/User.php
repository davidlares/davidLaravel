<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const VERIFIED = '1';
    const NOT_VERIFIED = '0';

    const ADMIN = 'true';
    const REGULAR = 'false';

    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'verified', 'verification_token', 'admin'
    ];

    protected $hidden = [
        'password', 'remember_token', 'verification_token'
    ];

    // mutators and accesors

    // mutators -> capitalizing before being a DB Record
    public function setNameAttribute($attr){
      $this->attributes['name'] = strtolower($attr);
    }

    // email mutator
    public function setEmailAttribute($attr){
      $this->attributes['email'] = strtolower($attr);
    }

    // accesor -> transforming the value (not for de BD w/o updating it)
    public function getNameAttribute($attr){
      return ucwords($attr);
    }

    public function isVerified(){
      return $this->verified == User::VERIFIED;
    }

    public function isAdmin(){
      return $this->admin = User::ADMIN;
    }

    public static function generateVerificationToken(){
        return str_random(40);
    }
}
