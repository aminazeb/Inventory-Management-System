<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Orion\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $model = User::class;
    protected $policy = UserPolicy::class;
    protected $resource = UserResource::class;

    public function includes(): array
    {
        return ['sales', 'purchases', 'roles', 'permissions'];
    }

    public function filterableBy(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone',
            'email_verified_at',
            'phone_verified_at',
            'created_at',
            'updated_at'
        ];
    }

    public function sortableBy(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone',
            'email_verified_at',
            'phone_verified_at',
            'created_at',
            'updated_at'
        ];
    }

    public function searchableBy(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone'
        ];
    }

    /**
     * The attributes that are allowed for creating a new model.
     */
    public function creatableFields(): array
    {
        return [
            'name',
            'email',
            'password',
            'phone'
        ];
    }

    /**
     * The attributes that are allowed for updating a model.
     */
    public function updatableFields(): array
    {
        return [
            'name',
            'email',
            'phone'
        ];
    }

    /**
     * Handle any actions before storing the model
     */
    protected function beforeStore(Request $request, $model)
    {
        // Hash password if provided
        if ($request->has('password')) {
            $model->password = bcrypt($request->password);
        }
    }

    /**
     * Handle any actions before updating the model
     */
    protected function beforeUpdate(Request $request, $model)
    {
        // Hash password if provided
        if ($request->has('password')) {
            $model->password = bcrypt($request->password);
        }
    }
}
