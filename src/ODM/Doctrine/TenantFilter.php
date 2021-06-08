<?php

namespace Almatar\Branding\ODM\Doctrine;

use Almatar\Branding\Services\BrandManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Query\Filter\BsonFilter;
use Doctrine\Common\Annotations\Reader;

class TenantFilter extends BsonFilter
{
    public function addFilterCriteria(ClassMetadata $targetDocument)
    {
        $parentClass = $targetDocument->getReflectionClass()->getParentClass();
        if (!$parentClass || $parentClass->getName() != 'Almatar\Branding\ODM\Doctrine\TenantModel') {
            return array();
        }
        $brandManager = app(BrandManager::class);
        $brands = ($brandManager->getUserBrands()) ? $brandManager->getUserBrands() : [$brandManager->getBrand()];
        return $brands[0] !== null ? array('brand' => array('$in' => $brands)) : array();
    }
}