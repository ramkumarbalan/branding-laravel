<?php


namespace Almatar\Branding\Scopes;

use Almatar\Branding\Services\BrandManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BrandingScope implements Scope
{
    private $brandManager;

    public function __construct(BrandManager $brandManager)
    {
        $this->brandManager = $brandManager;
    }

    public function apply(Builder $builder, Model $model)
    {
        $brands = ($brandManager->getUserBrands()) ? $brandManager->getUserBrands() : [$brandManager->getBrand()];
        $builder->whereIn('brand', $brands);
    }

}
