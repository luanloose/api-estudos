<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\Contracts\UserServiceContract;

class TransferRules
{
    protected $userService;

    public function __construct(UserServiceContract $user)
    {
        $this->userService = $user;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->userService->isSeller($request->payer_id)) {
            return response()->json(['error' => 'Unauthorized, only consumers can make payments'], 403);
        }

        return $next($request);
    }
}
