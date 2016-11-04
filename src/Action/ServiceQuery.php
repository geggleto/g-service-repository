<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-02
 * Time: 1:08 PM
 */

namespace G\Registry\Action;


use G\Core\Http\EndpointInterface;
use Predis\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RegisterService
 *
 * @package G\Registry\Action
 */
class ServiceQuery implements EndpointInterface
{
    /** @var Client  */
    protected $client;

    /** @var  array */
    protected $errors;

    /**
     * RegisterService constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->errors = array();
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        /** @var $response \Slim\Http\Response */

        //Get All URLS for a given Service
        $service = $args['name'];

        $urls = $this->client->smembers($service);

        return $response->withJson($urls);
    }

    /**
     * @param array $body
     *
     * @return bool
     */
    public function validate(array $body)
    {
        return true;
    }
}