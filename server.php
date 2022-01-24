<?php

require_once('db.php');

// Default value
$data['code'] = 500;
$data['message'] = 'Something went wrong';


if (!empty($_POST['city']) && !empty($_POST['operation'])) {

    $city = $_POST['city'];
    $operation = $_POST['operation'];

    // Getting from API operation
    if ($operation == 'getFromAPI') {
        $city = str_replace(' ', '-', $city);
        $old_key = 'e4b8b08c185638b825af37facfe1fabb';
        $key = 'dc1afa4e4ac696207224eee9d772cd35';
        $url = "http://api.openweathermap.org/data/2.5/forecast?q=$city&units=metric&appid=$key";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $errno = curl_errno($ch);
        $error  = curl_error($ch);
        curl_close($ch);

        if($result){
            $data['code'] = 200;
            $data['op'] = 'api';
            $data['data'] = $result;
        }
        else {
            $data['code'] = $errno;
            $data['message'] = $error;
        }   
    }
    // Getting from DB operation
    else if ($operation == 'getFromDB') {
        $forecast = getForecast($city, $data);
        if($forecast){
            $data['code'] = 200;
            $data['op'] = 'db';
            $data['data'] = $forecast;
        }
        else {
            $data['code'] = 404;
            $data['message'] = "'$city' does not exists in DB";
        }
    }
    // Saving forecast operation
    else if ($operation == 'saveForecast'){
        if ( !empty($_POST['forecast']) ) {

            $forecast = json_decode($_POST['forecast']);
            $city_name =  $forecast->city->name;
            $city_id = getCityId($city_name, $data);

            $params = [
                't_max' => $forecast->list[0]->main->temp_max, 
                't_min' => $forecast->list[0]->main->temp_min, 
                'w_speed' => $forecast->list[0]->wind->speed,
            ];
            // Is the city exists?
            if ($city_id){
                $params['city_id'] = $city_id;
                updateForecast($params, $data);
            }
            else {
                insertForecast($city_name, $params, $data);
            }
        }  
    }
    
} 

echo json_encode($data);