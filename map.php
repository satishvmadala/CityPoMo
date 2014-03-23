<?php
error_reporting(ALL);
ini_set('display_errors', 'On');
include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR  . 'functions/sensorfunctions.php');
 
$window = 'window';
$marker = 'marker';
?>
<!DOCTYPE html>
 <html>
   <head>
     <title>Google Maps JavaScript API v3 Example: Map Simple</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
     <meta charset="utf-8">
     <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
      .controls {
        margin-top: 16px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        padding: 0 11px 0 13px;
        width: 400px;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        text-overflow: ellipsis;
      }

      #pac-input:focus {
        border-color: #4d90fe;
        margin-left: -1px;
        padding-left: 14px;  /* Regular padding-left + 1. */
        width: 401px;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
}

    </style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
<script type="text/javascript"> 
function initialize() {
    var mapOptions = {
      center: new google.maps.LatLng(17.366,78.4760),
      zoom: 10      
       };
    var map = new google.maps.Map(document.getElementById("map-canvas"),
       mapOptions);

var input = /** @type {HTMLInputElement} */(
      document.getElementById('pac-input'));
map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.bindTo('bounds', map);

var infowindow = new google.maps.InfoWindow();
  var marker = new google.maps.Marker({
    map: map,
    anchorPoint: new google.maps.Point(0, -29)
  });

google.maps.event.addListener(autocomplete, 'place_changed', function() {
    infowindow.close();
    marker.setVisible(false);
    var place = autocomplete.getPlace();


    if (!place.geometry) {
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);


    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(15);  // Why 15? 17 looks even good.
    }
    marker.setIcon(/** @type {google.maps.Icon} */({
      url: place.icon,
      size: new google.maps.Size(71, 71),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(17, 34),
      scaledSize: new google.maps.Size(35, 35)
    }));
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);

    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join('');
    }
var latt = place.geometry.location.lat();
            var longit = place.geometry.location.lng();
            //alert("This function is working!");
            //alert(place.name);
           //alert(place.address_components[0].long_name);
    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address  +longit + latt);
    infowindow.open(map, marker);
});



<?php   
$allsensors = SensorsInArea(78.4760,17.366,0.0123);
foreach($allsensors as $Sensor){
$sensor = (string)$Sensor['_id'];

$SensorInfo = SensorInfo($sensor);

$SensorName = $SensorInfo['name'];


$longitude = SensorInfo($sensor)['loc']['coordinates']['0'];
$latitude = SensorInfo($sensor)['loc']['coordinates']['1'];

echo "
var $marker$sensor = new google.maps.Marker({
    position: new google.maps.LatLng($latitude,$longitude),
    map: map 
    });
var $window$sensor=new google.maps.InfoWindow({
    content: '<font color=red><a href=\"sensor.php?sensorid=$sensor\">$SensorName</font><br>";?>
<?php $TotalSensorData = PSensor24Hours($sensor); foreach($TotalSensorData as $SensorData){ 
echo  $SensorData['_id'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;----&nbsp;&nbsp;'.$SensorData['avg_value'];
echo "<br>";} ?></a>'
<?php
echo "
    });
 google.maps.event.addListener($marker$sensor, 'click', function() {    
    $window$sensor.open(map, $marker$sensor);
    });   ";  


}
?>


<?php   
$allsensors = SensorsInArea(+longit,+latti);
foreach($allsensors as $Sensor){
$sensor = (string)$Sensor['_id'];

$SensorInfo = SensorInfo($sensor);

$SensorName = $SensorInfo['name'];


$longitude = SensorInfo($sensor)['loc']['coordinates']['0'];
$latitude = SensorInfo($sensor)['loc']['coordinates']['1'];

echo "
var $marker$sensor = new google.maps.Marker({
    position: new google.maps.LatLng($latitude,$longitude),
    map: map 
    });
var $window$sensor=new google.maps.InfoWindow({
    content: '<font color=red><a href=\"sensor.php?sensorid=$sensor\">$SensorName</font><br>";?>
<?php $TotalSensorData = PSensor24Hours($sensor); foreach($TotalSensorData as $SensorData){ 
echo  $SensorData['_id'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;----&nbsp;&nbsp;'.$SensorData['avg_value'];
echo "<br>";} ?></a>'
<?php
echo "
    });
 google.maps.event.addListener($marker$sensor, 'click', function() {    
    $window$sensor.open(map, $marker$sensor);
    });   ";  


}
?>

  }
  google.maps.event.addDomListener(window, 'load', initialize);

     </script>
   </head>
  <body>
    <input id="pac-input" class="controls" type="text"
        placeholder="Enter a location">
   
    <div id="map-canvas"></div>
  </body>
 </html>
