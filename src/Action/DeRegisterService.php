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
use Slim\Http\Request;
use Slim\Http\Response;
use Valitron\Validator;

/**
 * Class RegisterService
 *
 * @package G\Registry\Action
 */
class DeRegisterService implements EndPointInterface
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
        $body = $request->getParsedBody();

        if (is_array($body) && $this->validate($body)) {

            //Atomically add services and urls
            foreach ($body['services'] as $service) {
                if ($this->client->sismember($service['name'], $service['url'])) {
                    $this->client->srem($service['name'], array($service['url']));
                }

                if ($this->client->sismember("callback-".$service['name'], $service['callback'])) {
                    $this->client->srem("callback-".$service['name'], array($service['callback']));
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