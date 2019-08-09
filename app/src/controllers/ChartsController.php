<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ChartsController extends BaseController
{

  public function web_historical_aq_chart_data(Request $request, Response $response, $args) {
        try {
            $user_data  = $request->getParsedBody();

            $chartdata = json_decode($this->sensor_db_model->get_historical_aq_data($user_data['date_start'],$user_data['date_end'], 
                                                                                    $user_data['usn'], $user_data['ssn']), true);
            

            if ($chartdata['result_code'] == 1) {
                foreach (array('CO', 'SO2', 'O3', 'NO2', 'PM2_5', 'temperature') as $sensor_label) {
                    // build array for Column labels
                    $json_array['cols'] = array(
                            array('id'=>'', 'label'=>'date/time', 'type'=>'string'),
                            array('id'=>'', 'label'=>$sensor_label, 'type'=>'number'));

                    // loop thru the sensor data and build sensor_array
                    foreach ($chartdata as $row) {
                        if(is_array($row)){
                            $sensor_array = array();
                            $sensor_array[] = array('v'=>$row['datetime_format']);
                            $sensor_array[] = array('v'=>$row[$sensor_label]);
                        
                            // add current sensor_array line to $rows
                            $rows[] = array('c'=>$sensor_array);
                        }
                    }
                
                    // add $rows to $json_array
                    $json_array['rows'] = $rows;
                    $rows = array();
                
                    $master_array[$sensor_label][] = $json_array;
                }
                
              return $response->withJson($master_array);

            } else {
                $json = array(
                    'result_code' => 2,
                    'message' => $chartdata['message']
                );
                return $response->withJson($json);
            }
        } catch(PDOException $e) {
            $json = array(
                'result_code' => 0,
                'message' => $e->getMessage()
            );
            return $response->withJson($json);
        }
    }

    
    public function android_historical_aq_chart_data(Request $request, Response $response, $args) {
        try {
            $user_data_json = file_get_contents('php://input');
            $user_data = json_decode($user_data_json, true);

            $chartdata = json_decode($this->sensor_db_model->get_historical_aq_data($user_data['date_start'],$user_data['date_end'], 
                                                                                    $user_data['usn'], $user_data['ssn']), true);
            

            if ($chartdata['result_code'] == 1) {
                foreach (array('CO', 'SO2', 'O3', 'NO2', 'PM2_5', 'temperature') as $sensor_label) {
                    // build array for Column labels
                    $json_array['cols'] = array(
                            array('id'=>'', 'label'=>'date/time', 'type'=>'string'),
                            array('id'=>'', 'label'=>$sensor_label, 'type'=>'number'));

                    // loop thru the sensor data and build sensor_array
                    foreach ($chartdata as $row) {
                        if(is_array($row)){
                            $sensor_array = array();
                            $sensor_array[] = array('v'=>$row['datetime_format']);
                            $sensor_array[] = array('v'=>$row[$sensor_label]);
                        
                            // add current sensor_array line to $rows
                            $rows[] = array('c'=>$sensor_array);
                        }
                    }
                
                    // add $rows to $json_array
                    $json_array['rows'] = $rows;
                    $rows = array();
                
                    $master_array[$sensor_label][] = $json_array;
                }
                
              return $response->withJson($master_array);

            } else {
                $json = array(
                    'result_code' => 2,
                    'message' => $chartdata['message']
                );
                return $response->withJson($json);
            }
        } catch(PDOException $e) {
            $json = array(
                'result_code' => 0,
                'message' => $e->getMessage()
            );
            return $response->withJson($json);
        }
    }
}
