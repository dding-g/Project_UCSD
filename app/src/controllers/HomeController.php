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

    //ANDROID AQ data return
    public function android_aq_data_response(Request $request, Response $response, $args)
    {
        $usn_json = file_get_contents('php://input');
        $usn_data = json_decode($usn_json, true);

    }
}
