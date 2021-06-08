<?php

namespace Almatar\Branding\ODM\Doctrine;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Almatar\Branding\Services\BrandManager;

/**
 * Tenant Model
 *
 */
class TenantModel {

    /** @ODM\Field(type="string") */
    public $brand;

    /** @ODM\PrePersist */
    public function prePersist(\Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $eventArgs)
    {
        $brandManager = app(BrandManager::class);
        if ($brandManager->getBrand()) {
            $this->brand = $brandManager->getBrand();
        }
    }
}