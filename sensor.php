<?php
include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR  . 'functions/sensorfunctions.php');

$sensorId = $_GET['sensorid'];

echo $sensorId;


print_r(PollutantAtSensor('so2',$sensorId));

?>
