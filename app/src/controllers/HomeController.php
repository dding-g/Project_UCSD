<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeController extends BaseController
{
    public function dispatch(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");

        $this->flash->addMessage('info', 'Sample flash message');

        $this->view->render($response, 'home.twig');
        return $response;
    }

    public function viewPost(Request $request, Response $response, $args)
    {
        $this->logger->info("View post using Doctrine with Slim 3");

        $messages = $this->flash->getMessage('info');

        try {
            $post = $this->em->find('App\Model\Post', intval($args['id']));
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }

        $this->view->render($response, 'post.twig', ['post' => $post, 'flash' => $messages]);
        return $response;
    }

    //View Homepage
    public function viewHome(Request $request, Response $response, $args)
    {
      $this->view->render($response, 'index_home.twig');
      return $response;
    }

    //WEB AQ data return
    public function web_aq_data_response(Request $request, Response $response, $args)
    {
        $json_data = $this->sensor_db_model->aq_realtime_data();

        return $response->withJson(json_decode($json_data));
    }

    //WEB AQ data return
    public function web_realtime_aq_data_move_map_response(Request $request, Response $response, $args)
    {   
        $data = $request->getParsedBody();

        $server_datetime = date("Y-m-d H:i:s");

        $json_data = $this->sensor_db_model->aq_realtime_data_move_map($data['lat_min'], $data['lng_min'], $data['lat_max'], $data['lng_max'], 
                                                                        date('Y-m-d H:i:s', strtotime("{$server_datetime} -1 days")));

        return $response->withJson(json_decode($json_data));
    }


    //ANDROID AQ data return
    public function android_realtime_aq_data_move_map_response(Request $request, Response $response, $args)
    {
        $data_json = file_get_contents('php://input');
        $data = json_decode($data_json, true);

        $server_datetime = date("Y-m-d H:i:s");

        return $this->sensor_db_model->aq_realtime_data_move_map($data['lat_min'], $data['lng_min'], $data['lat_max'], $data['lng_max'], 
                                                                    date('Y-m-d H:i:s', strtotime("{$server_datetime} -1 days")) );    
    }
}
