<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
class RoleMiddleware
{
    use ApiResponse;
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::user();
        if ($user->role == UserRole::ADMIN) {
            return $next($request);
        }
        if ($user->role != $role) {
            return $this->error('Unauthorized: You do not have permission to access this resource.', Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
