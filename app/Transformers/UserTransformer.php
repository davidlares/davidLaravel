<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identifier' => (int)$user->id,
            'fullname' => (string)$user->name,
            'emailaddress' => (string)$user->email,
            'verified' => (int)$user->verified,
            'administrator' => ($user->admin === 'true'),
            'createdDate' => (string)$user->created_at,
            'updatedDate' => (string)$user->updated_at,
            'deletedDate' => isset($user->deleted_at) ? (string) $user->deleted_at : null,
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
          'identifier' => 'id',
          'fullname' => 'name',
          'emailaddress' => 'email',
          'verified' => 'verified',
          'administrator' => 'admin',
          'createdDate' => 'created_at',
          'updatedDate' => 'updated_at',
          'deletedDate' => 'deleted_at'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
