<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Scope.
     */

    public function scopeGuardName($query, $value)
    {
        return $query->where('guard_name', $value);
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }
}
