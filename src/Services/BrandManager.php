<?php

namespace Almatar\Branding\Services;

use Almatar\Branding\Exceptions\BrandNotFoundException;
use Almatar\Branding\Exceptions\BrandPermissionException;
use Almatar\Branding\Services\Communicators\AuthCommunicator;


class BrandManager
{

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @var array
     */
    protected $brands;

    /**
     * @var array
     */
    protected $userBrands = [];

    /**
     * @var string
     */
    protected $brand;

    /**
     * @var AuthCommunicator
     */
    private $authCommunicator;

    /**
     * BrandManager constructor.
     */
    public function __construct(AuthCommunicator $authCommunicator)
    {
        $this->brands = ['almatar', 'new-travel'];
        $this->authCommunicator = $authCommunicator;
    }

    /**
     * Enable scoping by brandColumns.
     *
     * @return void
     */
    public function enable()
    {
        $this->enabled = true;
    }

    /**
     * Disable scoping by brandColumns.
     *
     * @return void
     */
    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * @return string|Model
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @return array
     */
    public function getUserBrands()
    {
        return $this->userBrands;
    }

    /**
     * Whether a brand is currently being scoped.
     *
     * @param string $brand
     *
     * @return bool
     * @throws BrandPermissionException
     */
    private function hasBrand($brand)
    {
        if (!empty($this->userBrands) && !in_array($brand, $this->userBrands)) {
            throw new BrandPermissionException('permission denied');
        }
        return in_array($brand, $this->brands);
    }

    /**
     * Load brand.
     *
     * @param string $brand
     *
     * @return string $brand
     * @throws BrandPermissionException
     * @throws BrandNotFoundException
     */
    public function loadBrand($brand)
    {
        if (!$this->hasBrand($brand)) {
            throw new BrandNotFoundException('$brand not found');
        }
        $this->brand = $brand;
        return $brand;
    }

    /**
     * Validate brand.
     *
     * @param Request $request
     *
     * @throws BrandPermissionException
     */
    public function loadUserBrands($request)
    {
        $this->userBrands = $this->brands;
        return;
        // Set user brands from header
        if ($request->header('X-EmployeeBrands')) {
            $this->userBrands = explode(",", $request->header('X-EmployeeBrands'));
            return;
        }
        // Get User brands
        $response = $this->authCommunicator->getUserBrands([]);
        if ($response['status'] == 200) {
            $this->userBrands = array_column($response['data'], 'slug');
        } else {
            throw new BrandPermissionException('$user_token is not valid');
        }
    }
}
