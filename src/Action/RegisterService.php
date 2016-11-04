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
use Slim\Http\Request;
use Slim\Http\Response;
use Valitron\Validator;

/**
 * Class RegisterService
 *
 * @package G\Registry\Action
 */
class RegisterService implements EndpointInterface
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
        $body = $request->getParsedBody();

        if (is_array($body) && $this->validate($body)) {

            //Atomically add services and urls
            foreach ($body['services'] as $service) {
                if (!$this->client->sismember($service['name'], $service['url'])) {
                    $this->client->sadd($service['name'], array($service['url']));
                }

                if (!$this->client->sismember("callback-".$service['name'], $service['callback'])) {
                    $this->client->sadd("callback-".$service['name'], array($service['callback']));
                }
            }

            return $response->withJson(array("message" => "Operation Successful"));

        } else {
            return $response->withJson($this->errors, 400);
        }
    }

    /**
     * @param array $body
     *
     * @return bool
     */
    public function validate(array $body)
    {
        $validator = new Validator($body);
        $validator->rule('required', array('services', 'services.*.callback', 'services.*.name', 'services.*.url'));

        $this->errors = $validator->errors();
        return $validator->validate();
    }
}