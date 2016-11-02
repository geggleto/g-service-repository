<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-02
 * Time: 1:08 PM
 */

namespace G\Registry\Action;


use Predis\Client;
use Slim\Http\Request;
use Slim\Http\Response;
use Valitron\Validator;

/**
 * Class RegisterService
 *
 * @package G\Registry\Action
 */
class ServiceQuery implements IAction
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
     * Action code
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
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