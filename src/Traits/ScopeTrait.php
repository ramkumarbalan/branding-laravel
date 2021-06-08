<?php


namespace Almatar\Branding\Traits;


use Almatar\Branding\Scopes\BrandingScope;
use Almatar\Branding\Services\BrandManager;
use Illuminate\Database\Eloquent\Model;

trait ScopeTrait
{
    public static function bootScopeTrait()
    {
        $brandManager = app(BrandManager::class);

        static::addGlobalScope(new BrandingScope($brandManager));
        
        static::creating(function (Model $model) use ($brandManager) {
            $brand = $brandManager->getBrand();
            $model->setAttribute('brand', $brand);
        });
    }
}