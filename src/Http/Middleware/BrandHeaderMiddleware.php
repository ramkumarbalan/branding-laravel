<?php


namespace Almatar\Branding\Http\Middleware;

use Almatar\Branding\Services\BrandManager;
use Psr\Http\Message\RequestInterface;

class BrandHeaderMiddleware
{
    static function addBrandHeader()
    {
        return function (callable $handler) {
            return function (
                RequestInterface $request,
                array $options
            ) use ($handler) {
                $manager = app(BrandManager::class);
                $request->withHeader('X-Brand', $manager->getBrand());
                $request->withHeader('X-Employee-Brands', implode(",", $manager->getUserBrands()));
                return $handler($request, $options);
            };
        };
    }
}