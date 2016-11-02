<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-02
 * Time: 1:09 PM
 */

namespace G\Registry\Action;


use Slim\Http\Request;
use Slim\Http\Response;

interface IAction
{
    public function __invoke(Request $request, Response $response, array $args);
    public function validate(array $data);
}