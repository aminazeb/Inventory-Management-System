<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * Scope.
     */

    public function scopeGuardName($query, $value)
    {
        return $query->where('guard_name', $value);
    }

    public function scopeName($query, $value)
    {
        return $query->where('name', $value);
    }
}
