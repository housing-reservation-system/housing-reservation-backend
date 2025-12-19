<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\StatusType;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;
class StatusMiddleware
{
    use ApiResponse;
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array(Auth::user()->status, [StatusType::APPROVED->value])) {
            return $this->error('Unauthorized: You do not have permission to access this resource.', Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
