<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isLoginRoute = Route::getCurrentRoute()?->getController() instanceof AuthController && Str::after(Route::getCurrentRoute()->getActionName(), '@') === 'login';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'password' => $this->password,
            'phone_verified_at' => $this->phone_verified_at,
            'token' => $this->when($isLoginRoute, fn() => $this->createToken('auth_token')->plainTextToken),
        ];
    }
}
