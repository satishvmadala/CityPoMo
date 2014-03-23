<?php
include_once(dirname(__FILE__) .DIRECTORY_SEPARATOR . 'dbfunctions.php');

//Sensor Info
function SensorInfo($sensor_id) {
	$sensor_query = array('_id'=>new MongoId($sensor_id));
	$sensor_info = FindOneInCollection('Sensors', $sensor_query);
	return 	$sensor_info;	
}

/**
* Function Aggregates the Data at a particular Sensor and Particular Pollutant
* @Param string to pass into the function are $pollutant and $sensor_id
**/


function PollutantAtSensor($pollutant,$sensor_id){

$sdb = dbconnect();
$ac = new MongoCollection($sdb,'SensorData');
$PollutantData = $ac->aggregate(
array('$match' => array('sensor_id' =>$sensor_id)),
array('$unwind' => '$fields'),
array('$match' => array('fields.name' => $pollutant )),
array('$project' => array('fields.name' => 1,'fields.value' => 1,'fields.lastUpdate' => 1,'_id' => 0))
);
$AllPollutantData = $PollutantData['result'];
return $AllPollutantData;
}



//For each Pollutant type from a Single Sensor during a Date Range
function PSensorDateRange($pollutant,$sensor_id,$daterange1,$daterange2){

$sdb = dbconnect();
$ac = new MongoCollection($sdb,'SensorData');
$PollutantData = $ac->aggregate(
array('$match' => array('sensor_id' =>$sensor_id)),
array('$unwind' => '$fields'),
array('$match' => array('fields.name' => $pollutant )),
array('$match' => array('fields.lastUpdate' => array(
        '$gte' => $daterange1,
        '$lte' => $daterange2
    ))),
array('$project' => array('fields.name' => 1,'fields.value' => 1,'fields.lastUpdate' => 1,'_id' => 0))
);
$AllPollutantData = $PollutantData['result'];
return $AllPollutantData;
}

/**
Sensors in a Area with Maximum Distance of $distance
**/


function SensorsInArea($longitude, $lattitude,$distance){

$sdb = dbconnect();
$sc = new MongoCollection($sdb,'Sensors');
$lnglat = array($longitude, $lattitude);
$SensorsinArea = $sc->aggregate(
array('$geoNear'=>array('near' => $lnglat, 'maxDistance' =>$distance, 'distanceField'=>'dist.calculated', 'spherical' => true)),
array('$project' => array('_id' => 1))
);
$AllSensors = $SensorsinArea['result'];
return $AllSensors;
}


//For each Pollutant type from a Single Sensor during a Date Range
function PSensor24Hours($sensor_id){

$sdb = dbconnect();
$ac = new MongoCollection($sdb,'SensorData');
$daterange2 = date("Y-m-dTH:m:s", strtotime('0 hours', time())); 


$daterange1 = date("Y-m-dTH:m:s", strtotime('-24 hours', time())); 

$PollutantData = $ac->aggregate(

array('$match' => array('sensor_id' =>$sensor_id)),
array('$unwind' => '$fields'),

//array('$match' => array('fields.name' => $pollutant )),
array('$match' => array('fields.lastUpdate' => array(
        '$gte' => $daterange1,
        '$lte' => $daterange2
    ))),
array('$group' => array(
   '_id'=> '$fields.name',
'avg_value'=> array('$avg' => '$fields.value')
   ))
//array('$project' => array('fields.longName' => 1,'fields.name' => 1,'avg.value' => 1,'_id' => 1))
);
$AllPollutantData = $PollutantData['result'];
return $AllPollutantData;
}



//For Aggregated Data of particular Pollutant of all Sensors in a particular area
//We will get the sensor_ids-array after user selects particular area.
function SensorsDataInArea($sensorids){

$sdb = dbconnect();
$ac = new MongoCollection($sdb,'SensorData');
$AreaSensorsData = array();
foreach($sensorids as $sensor_id){
$AreaSensorsData =PSensor24Hours($sensor_id);
}

return $AreaSensorsData;
}












//Total Sensors Data, I ain't figured it out, Where to be this function used
function TotalSensorsData(){

$sdb = dbconnect();
$ac = new MongoCollection($sdb,'SensorData');
$SensorsData = $ac->aggregate(
array('$unwind' => '$fields'),
array('$project' => array( 'fields.name' => 1,'fields.value' => 1,'fields.lastUpdate' => 1,'_id' => 0))
);
$AllSensorsData = $SensorsData['result'];
return $AllSensorsData;
}


?>
