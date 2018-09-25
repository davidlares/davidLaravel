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
            // hateoas
            'links' => [
              ['rel' => 'self', 'href' => route('users.show', $user->id)]
            ]
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

    public static function transformedAttribute($index){
        $attributes = [
          'id' => 'identifier',
          'name' => 'fullname',
          'email' => 'emailaddress',
          'verified' => 'verified',
          'admin' => 'administrator',
          'created_at' => 'createdDate',
          'updated_at' => 'updatedDate',
          'deleted_at' => 'deletedDate'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
