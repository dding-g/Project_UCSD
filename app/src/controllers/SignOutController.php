<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Slim\Container;

final class SignOutController
{
    
  public function __construct(Container $c)
  {
      $this->db_model = $c->get('DBModel');
  }

  public function sign_out_process(Request $request, Response $response, $args)  {

    $user_data = $request->getParsedBody();

    $result_json = $this->db_model->is_sign_out_update($user_data['usn']);
    $result = json_decode($result_json);

    return $response->withJson(json_encode($result));
  }
  
  public function android_sign_out_process(Request $request, Response $response, $args)  {

    $user_data_json = file_get_contents('php://input');
    $user_data = json_decode($user_data_json, true);

    return $this->db_model->is_sign_out_update($user_data['usn']);
  }

}
