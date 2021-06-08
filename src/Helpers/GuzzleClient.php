<?php

namespace Almatar\Branding\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

/**
 * Oct 14, 2018
 * Description of GuzzleClient.
 *
 * @author Mohamed Shehata <mohamed.shehata@almtar.com>
 */
class GuzzleClient
{
    /**
     * @var Client
     */
    private $client = null;

    /**
     * @var int
     */
    private $status = 200;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var string
     */
    private $body = null;

    /**
     * @param Client $client
     */
    public function __construct()
    {
        $this->client = $this->initClient();
    }

    private function initClient(): Client
    {
        // Create the default HandlerStack
        $stack = HandlerStack::create();

        // Push the Middleware to the stack
        // $stack->push(cronJobMiddleware::addCronJobHeader());

        // Create the client
        return new Client(['handler' => $stack]);
    }

    /**
     * @param string $action
     * @param string $uri
     * @param array  $body
     * @param array  $headers
     * @param string $requestForm
     */
    public function request(string $action, string $uri, array $body = [], array $headers = [], string $requestForm = RequestOptions::JSON)
    {
        $headers = $this->getBaseRequestHeaders($headers);
        $start = microtime(true);
        // AlmtarLog::info('Sending guzzle request to: '.$uri.' with '.$action.' method with type: '.$requestForm, compact('headers', 'body'));

        try {
            $response = $this->client->request($action, $uri, [
                $requestForm => $body,
                'headers' => $headers,
            ]);

            $this->status = $response->getStatusCode();
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            $this->status = $e->getCode();
        }

        $this->headers = $response->getHeaders();

        $this->body = $response->getBody()->getContents();

        $elapsedTime = round(microtime(true) - $start, 2);
        // AlmtarLog::info('Guzzle Response from: '.$uri.' after: '.$elapsedTime.' seconds with status: '.$this->getStatus(), (($this->getStatus() != 200)?['request'=>$body,'response'=>$this->getBody()] :[]));
    }

    private function getBaseRequestHeaders(array $headers): array {
        $headers = array_merge($headers, ['Accept-encoding' => ['gzip', 'deflate']]);
        $authHeader = app('request')->headers->get('Authorization');
        if (!empty($authHeader)) {
            $headers['Authorization'] = $authHeader;
        }
        return $headers;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return json_decode($this->body, true);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        $keys = array_keys($this->headers);
        $values = array_column($this->headers, 0);

        return array_combine($keys, $values);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}
