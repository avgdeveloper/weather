<?php

define('DB_CONFIG', 'mysql:host=localhost;dbname=weather;charset=utf8');
define('DB_USER', 'root');
define('DB_PASSWORD', '');


// Getting a forecast according the city name
// params: $city(string)- name of the city, $data(array, by-refernce)- external variable for getting the PDO Exception
// returns the specific city data

function getForecast($city, &$data) {
    try {
        $pdo = new PDO(DB_CONFIG, DB_USER, DB_PASSWORD);
        $sql = 'SELECT city.*,temp.*,wind.* FROM city ' 
                .'JOIN temp ON city.c_id = temp.t_city_id ' 
                .'JOIN wind ON city.c_id = wind.w_city_id ' 
                .'WHERE city.c_city_name = ?';
        $query = $pdo->prepare($sql);
        $query->execute([$city]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        $data['code'] = 500;
        $data['message'] =  "Connection failed: " . $e->getMessage();
    }
}


// Updating the forecast according the city ID
// params: $params(associate array)- contains the params for update query, $data(array, by-refernce)- external variable for getting the PDO Exception
// returns nothin

function updateForecast($params, &$data){
    try {
        $pdo = new PDO(DB_CONFIG, DB_USER, DB_PASSWORD);
        $sql = 'UPDATE city SET updated_at = NOW() WHERE city.c_id = :city_id;'
               .'UPDATE temp SET t_temp_max = :t_max, t_temp_min = :t_min, t_date_time = NOW() WHERE t_city_id = :city_id,'
               .'UPDATE wind SET w_speed = :w_speed where w_city_id = :city_id';
        $query = $pdo->prepare($sql);
        $query->execute($params);

        $data['code'] = 200;
        $data['message'] =  "Forecast updated successfully";
    }
    catch (PDOException $e) {
        $data['code'] = 500;
        $data['message'] =  "Connection failed: " . $e->getMessage();
    }
}


// Inserting forecast (first creating row in the city table, fetching the city ID, inserting the rest data related to city ID)
// params: $city_name(string) - city name, $param(associate array) - for the second query, $data(array, by-refernce) - external variable for getting the PDO Exception
// returns nothing

function insertForecast($city_name, $params, &$data) {
    try {
        $pdo = new PDO(DB_CONFIG, DB_USER, DB_PASSWORD);
        $sql = "INSERT INTO city VALUES('', ?, NOW(), NOW())";
        $query = $pdo->prepare($sql);
        $query->execute([$city_name]);

        $params['city_id'] = $pdo->lastInsertId();
        $sql = "INSERT INTO temp values('',:t_max, :t_min, NOW(), :city_id);" 
              ."INSERT INTO wind values('', :w_speed, :city_id)";
        $query = $pdo->prepare($sql);
        $query->execute($params);

        $data['code'] = 200;
        $data['message'] =  "forecast inserted successfully";
    }
    catch (PDOException $e) {
        $data['code'] = 500;
        $data['message'] =  "Connection failed: " . $e->getMessage();
    }
}


 // Getting the city-ID according the city-name
// params: $city_name(string) - city-name, $data(array, by-refernce) - external variable for getting the PDO Exception
// returns city ID (int)

function getCityId($city_name, &$data){
    try {
        $pdo = new PDO(DB_CONFIG, DB_USER, DB_PASSWORD);
        $query = $pdo->prepare('SELECT * FROM city WHERE c_city_name = ? ');
        $query->execute([$city_name]);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        return $res['c_id'];
    }
    catch (PDOException $e) {
        $data['code'] = 500;
        $data['message'] =  "Connection failed: " . $e->getMessage();
    }
}


