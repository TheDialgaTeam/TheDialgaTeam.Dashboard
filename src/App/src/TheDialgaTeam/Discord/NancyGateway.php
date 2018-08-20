<?php

namespace App\TheDialgaTeam\Discord;

use App\Constant\Error;
use App\TheDialgaTeam\Discord\Module\BaseModule;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Hydrator\ClassMethods;
use Zend\Json\Json;

/**
 * Class NancyGateway
 * @package App\TheDialgaTeam\Discord
 */
class NancyGateway
{
    /**
     * @var BaseModule
     */
    public $baseModule;

    /**
     * Nancy gateway url.
     * @var string
     */
    private $url;

    /**
     * Nancy gateway port.
     * @var int
     */
    private $port;

    /**
     * NancyGateway constructor.
     * @param array $config Nancy gateway options.
     */
    public function __construct($config)
    {
        $this->url = $config['url'];
        $this->port = $config['port'];

        $this->baseModule = new BaseModule($this);
    }

    /**
     * @param string $clientId
     * @param string $guildId
     * @return bool
     * @throws \Exception
     */
    public function checkBotExist($clientId, $guildId)
    {
        $route = "checkBotExist/clientId/$clientId/guildId/$guildId";
        $jsonArray = $this->getResponseFromServer($route, Request::METHOD_GET);

        $hydrator = new ClassMethods();

        /** @var NancyResult $result */
        $result = $hydrator->hydrate($jsonArray, new NancyResult());

        return $result->isSuccess();
    }

    /**
     * @param string $route
     * @param string $method
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function getResponseFromServer($route, $method, $params = array())
    {
        try {
            $client = new Client();

            $request = new Request();
            $request->setUri($this->generateAPIUrl($route));
            $request->setMethod($method);

            if (is_array($params) && count($params) > 0) {
                if ($method == Request::METHOD_GET) {
                    foreach ($params as $key => $value) {
                        $request->getQuery()->set($key, $value);
                    }
                } elseif ($method == Request::METHOD_POST || $method == Request::METHOD_PATCH || $method == Request::METHOD_PUT) {
                    foreach ($params as $key => $value) {
                        $request->getPost()->set($key, $value);
                    }
                }
            }

            $response = $client->send($request);
            $json = $response->getBody();

            return Json::decode($json, Json::TYPE_ARRAY);
        } catch (\Exception $ex) {
            throw new \Exception(Error::ERROR_NANCY_GATEWAY);
        }
    }

    /**
     * @param string $endpoint Nancy gateway route.
     * @return string
     */
    private function generateAPIUrl($endpoint)
    {
        return sprintf('%s:%s/%s', $this->url, $this->port, $endpoint);
    }
}