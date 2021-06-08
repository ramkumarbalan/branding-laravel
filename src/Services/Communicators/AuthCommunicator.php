<?php

namespace Almatar\Branding\Services\Communicators;

use Almatar\Branding\Helpers\GuzzleClient;

/**
 * Description of AuthCommunicator.
 *
 * @author amira.nasrullah
 */
class AuthCommunicator
{
    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * @param GuzzleClient $client
     */
    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $request
     *
     * @return array
     */
    public function getUserBrands(array $request)
    {
        $this->client->request('get', env('GET_USER_BRANDS_ENDPOINT'), $request);
        return $this->client->getBody();
    }
}
