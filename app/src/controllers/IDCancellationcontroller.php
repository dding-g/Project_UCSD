<?php
/*이메일 입력 -> 이메일 db에 존재하는지 확인 -> 존재하면 메일 발송 메세지, 존재 x 에러메세지, -> 메일 인증 시, 페이지 넘어가 새로운 패스워드*/
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


final class IDCancellationcontroller extends BaseController
{
  public function idCancellation_view(Request $request, Response $response, $args)
    {
		$this->view->render($response, 'idcancellation.twig');
		return $response;
    }


    public function idCancellation_process(Request $request,Response $response,$args){
      $id_cancelltion_data_user = $request->getParsedBody();

      $result_code = $this->db_model->check_password_and_id_cancellation($id_cancelltion_data_user);

      return $response->withJson(json_decode($result_code));
    }

    public function android_idCancellation_process(Request $request,Response $response,$args){
      $id_cancelltion_data_user_json = file_get_contents('php://input');
      $id_cancelltion_data_user = json_decode($id_cancelltion_data_user_json, true);  

      return  $this->db_model->check_password_and_id_cancellation($id_cancelltion_data_user);
    }
}
