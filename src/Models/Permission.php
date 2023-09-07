<?php

namespace BytePlatform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'group', 'slug'];

    public function roles()
    {
        return $this->belongsToMany(config('byteplatform.model.role'), 'roles_permissions');
    }

    public function users()
    {
        return $this->belongsToMany(config('byteplatform.model.user'), 'users_permissions');
    }
}
