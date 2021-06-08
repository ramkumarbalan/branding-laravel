<?php

namespace Almatar\Branding\Http\Middleware;

use Almatar\Branding\Exceptions\BrandNotFoundException;
use Almatar\Branding\Exceptions\BrandPermissionException;
use Almatar\Branding\Services\BrandManager;
use Closure;

class IdentifyBrand
{
    /**
     * @var App\Services\BrandManager
     */
    protected $brandManager;

    public function __construct()
    {
        $this->brandManager = app(BrandManager::class);
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws BrandPermissionException
     * @throws BrandNotFoundException
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('X-Brand')) {
            // B2C requests
            $this->handleB2CRequests($request);
        } elseif ($request->header('Authorization') || $request->header('X-Employee-Brands')) {
            // Console requests
            $this->handleConsoleRequests($request);
        } else {
            // TODO: handle it again to throw unauthorized error when handle employee brands
            $this->handleConsoleRequests($request);
            // throw error
            // throw new BrandPermissionException('permission denied');
        }
        return $next($request);
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @return mixed
     * @throws BrandPermissionException
     * @throws BrandNotFoundException
     */
    private function handleB2CRequests($request)
    {
        return $this->brandManager->loadBrand($request->header('X-Brand'));
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @return mixed
     * @throws BrandNotFoundException
     * @throws BrandPermissionException
     */
    private function handleConsoleRequests($request)
    {
        // Load user brands
        $this->brandManager->loadUserBrands($request);
        // Load brand
        if ($request->input('brand')) {
            $this->brandManager->loadBrand($request->input('brand'));
        }
    }
}