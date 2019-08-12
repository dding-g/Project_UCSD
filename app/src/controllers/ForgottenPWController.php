<?php
/*이메일 입력 -> 이메일 db에 존재하는지 확인 -> 존재하면 메일 발송 메세지, 존재 x 에러메세지, -> 메일 인증 시, 페이지 넘어가 새로운 패스워드*/
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


final class ForgottenPWController extends BaseController
{

	//FP view
	public function forgottenPW_view(Request $request, Response $response, $args)
    {
		$this->view->render($response, 'forgot-password.twig');
		return $response;
	}
	
	
	//verification code stored in db
	public function update_verification_cpw_process($email){
		$user_data = json_decode($this->db_model->getUserInfo($email), true);
		$usn = $user_data['usn'];

		if($user_data['result_code'] == 1){
			$user_data['verification_code_cpw'] = md5((string)$usn);
			$update_cpw_status = json_decode($this->db_model->update_verification_cpw($user_data['verification_code_cpw'], $usn), true);
			
			if($update_cpw_status['result_code'] == 1)
				return json_encode($user_data['verification_code_cpw']);
			else
				return json_encode($update_cpw_status);
				
		}else{
			return json_encode($user_data);
		}
		

	}

	//WEB first request
	public function forgotten_password_process(request $request,response $response, $args){
		$user_data = $request->getParsedBody();
		$email_status = $this->db_model->getByEmail($user_data['email']);
		$user_data['verification_code_cpw'] = $this->update_verification_cpw_process($user_data['email']);

		//CASE 1 : SUCCESS
		if(json_decode($email_status, true)['result_code'] == 1){
			$this->emailconfig->sendMail($user_data['email'],$user_data['verification_code_cpw'],"authorized_user_fp");
		//var_dump(set_veri_code_cpw($user_data['verification_code_cpw']));
			echo("<script language=javascript>
							alert('we send you an email!! please check your email!!');
							location.href='http://teama-iot.calit2.net/';
						</script>");

		}
		else{
			echo("<script language=javascript>
							alert('your not our client!! check your email address!!');
							location.href='http://teama-iot.calit2.net/';
						</script>");
		}

	}


	//ANDROID first request
	public function android_forgotten_password_process(Request $request, Response $response, $args){
		$user_data_json = file_get_contents('php://input');
		$user_data = json_decode($user_data_json, true);  
		$email_status = $this->db_model->getByEmail($user_data['email']);
		$user_data = json_decode($this->update_verification_cpw_process($user_data['email']), true);

		if($user_data['result_code'] != 1 || json_decode($email_status, true)['result_code'] != 1){
			return json_encode($user_data);
		}
		//CASE 1 : SUCCESS
		if(json_decode($email_status, true)['result_code'] == 1){
			$this->emailconfig->sendMail($user_data['email'],$user_data['verification_code_cpw'],"authorized_user_fp");
			$json = array(
				'result_code' => 1,
				'message' => 'success'
			);
			return json_encode($json);
		}
		else{
			return json_encode(json_decode($json, true));
		}

	}



	//Forgotten Password EMAIL authorized process
	public function user_authorized_fp_process(Request $request, Response $response, $args)
	{

		$user_data = json_decode($this->db_model->getUserData_Using_cpw($args['verification_code_cpw']), true);
		//when comparing true, INSERT user_authorized table and DELETE user_temporary table tuple

		//success authorized
		if($user_data['result_code'] == 1)
		{	
			$verification_code_cpw = $user_data['verification_code_cpw'];
			header("Location: http://teama-iot.calit2.net/qi/user/forgotten_password/password_change/{$verification_code_cpw}");
			die();
		}else{ //fail authorized
			echo ("<script language=javascript>
						alert('wrong verification code!!');
				</script>");
			header("Location: http://teama-iot.calit2.net");
			die();
		}

	}

	//Forgotten password - View
	public function forgottenPW_changePW_view(Request $request, Response $response, $args)
    {
		$this->view->render($response, 'forgotten_password_change.twig');

		return $response;
	}

	//Forgotten password - ChangePasswrod process
	public function forgottenPW_changePW_process(Request $request, Response $response, $args)
    {
		$user_data = $request->getParsedBody();

		$result = $this->db_model->forgottenPW_change_password_using_usn(password_hash($user_data['new_password'], PASSWORD_DEFAULT), $user_data['usn']);

		return $response->withJson(json_decode($result, true));
	}

	//Forgotten password - get USN
	public function forgottenPW_getUsn(Request $request, Response $response, $args)
    {
		$user_data = $request->getParsedBody();
		$result = $this->db_model->getUserData_Using_cpw($user_data['verification_code_cpw']);

		return $response->withJson(json_decode($result, true));
	}




}
