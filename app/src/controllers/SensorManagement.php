<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Slim\Container;

final class SensorManagement extends BaseController
{

  //Sensor Registration
  public function sensor_registration_process(Request $request, Response $response, $args)  {

    $sensor_data_json = file_get_contents('php://input');
    $sensor_data = json_decode($sensor_data_json, true);  
    
    return $this->sensor_db_model->sensor_registration($sensor_data);
  }

  //WEB : Sensor DeRegistration
  public function web_sensor_deregistration_process(Request $request, Response $response, $args)  {
    $dereg_sensor = $request->getParsedBody();
    
    return $this->sensor_db_model->sensor_Deregistration($dereg_sensor);
}

  //ANDROID : Sensor DeRegistration
  public function android_sensor_deregistration_process(Request $request, Response $response, $args)  {

      $dereg_sensor_json = file_get_contents('php://input');
      $dereg_sensor = json_decode($dereg_sensor_json, true);  
      
      return $this->sensor_db_model->sensor_Deregistration($dereg_sensor);
  }
  
  //Air Quality Data Record (include Real-time table)
  public function airQuality_record(Request $request, Response $response, $args)  {

    $air_q_data_json = file_get_contents('php://input');
    $air_q_data = json_decode($air_q_data_json, true);  

    $air_q_status = $this->sensor_db_model->air_quality_data_record($air_q_data["data"]);

    return $air_q_status;
  }

  //HeartRate Data Record 
  public function heart_rate_record(Request $request, Response $response, $args)  {

    $heart_r_data_json = file_get_contents('php://input');
    $heart_r_data = json_decode($heart_r_data_json, true);

    return $this->sensor_db_model->heartrate_data_record($heart_r_data);
  }


  //display device information
  public function android_device_list_view_process(Request $request, Response $response, $args)  {
    $usn_json = file_get_contents('php://input');
    $usn = json_decode($usn_json, true);  

    return $this->sensor_db_model->get_deviceList_usn($usn['usn']);
  }

  
  //WEB : display device information 
  public function web_device_list_view_process(Request $request, Response $response, $args)  {
    $usn = $request->getParsedBody();
    return $response->withjson(json_decode( $this->sensor_db_model->get_deviceList_usn($usn['usn'] )));
  }

  //WEB : display device information 
  public function web_device_list_using_ssn_process(Request $request, Response $response, $args)  {
    $data = $request->getParsedBody();
    return $response->withjson(json_decode( $this->sensor_db_model->get_deviceList_ssn($data['ssn'] )));
  }

  //WEB : get historical aq data using ssn AND datetime
  public function web_get_historical_aq_chart_data(Request $request, Response $response, $args)
  {
    $aq_data = $request->getParsedBody();
    $result_code = $this->sensor_db_model->get_historical_aq_data_using_userdata($aq_data['date_start'], $aq_data['date_end'], $aq_data['usn'], $aq_data['ssn']);

    return $response->withJson(json_decode($result_code));
  }
  
  
  //Android : get historical aq data
  public function mobile_get_historical_aq_data(Request $request, Response $response, $args)
  {
    $aq_data_json = file_get_contents('php://input');
    $aq_data = json_decode($aq_data_json, true);

    return $this->sensor_db_model->get_historical_aq_data_using_userdata($aq_data['date_start'], $aq_data['date_end'], $aq_data['usn'], $aq_data['ssn']);
  }

  //Android : get historical hr data
  public function mobile_get_historical_hr_data(Request $request, Response $response, $args)
  {
    $hr_data_json = file_get_contents('php://input');
    $hr_data = json_decode($hr_data_json, true);

    return $this->sensor_db_model->get_historical_hr_data($hr_data);
  }

  //Android : get historical hr data Using location  datetime
  public function mobile_get_historical_hr_data_using_loc_datetime(Request $request, Response $response, $args)
  {
    $hr_data_json = file_get_contents('php://input');
    $hr_data = json_decode($hr_data_json, true);

    return $this->sensor_db_model->get_realtime_hr_data_using_loc_date($hr_data);
  }

  //view Air quality historical data page
  public function aq_historical_view(Request $request, Response $response, $args)  {
    $this->view->render($response, 'aq_historical.twig');
    return $response;
  }

  //view heart_rate_realtime_view page
  public function heart_rate_realtime_view(Request $request, Response $response, $args)  {
    $this->view->render($response, 'hr_data_realtime_view.twig');
    return $response;
  }

    //view heart_rate_historical_view page
  public function heart_rate_historical_view(Request $request, Response $response, $args)  {
    $this->view->render($response, 'hr_data_historical_view.twig');
    return $response;
  }

  //real time heart rate data
  public function web_heart_rate_realtime_data_process(Request $request, Response $response, $args)  {
    $hr_data = $request->getParsedBody();
    $result = $this->sensor_db_model->get_realtime_hr_data($hr_data['usn']);

    return $response->withJson(json_decode($result));
  }

  //historical heart rate data
  public function web_heart_rate_historical_data_process(Request $request, Response $response, $args)  {
    $hr_data = $request->getParsedBody();
    $result = json_decode($this->sensor_db_model->get_historical_hr_data($hr_data), true);

      // loop thru the sensor data and build sensor_array
      foreach ($result as $row) {
          if(is_array($row)){
              $heart_rate_data = array(
                                    'date'=>$row['datetime'],
                                    'value'=>$row['heart_rate']
                                  );
          
              // add current sensor_array line to $rows
              $rows[] = $heart_rate_data;
          }
      }
  
    return $response->withJson($rows);
  }

}
