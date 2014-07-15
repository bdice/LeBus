var lat, lon, vehicle_id, route_id;

var updateLocation = function(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(handleGetCurrentPosition, onError);
    }
};

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

$(document).ready(function(){
  $('#vehicle_id').val(getRandomInt(1, 20));
  $('#route_id').val(getRandomInt(33, 57));
  updateLocation();
});

function handleGetCurrentPosition(location){

    lat = location.coords.latitude;
    lon = location.coords.longitude;
    $('#lat').val(lat);
    $('#lon').val(lon);
    console.log("Update: "+lat+","+lon);

}

function onError(){
    console.log("Error!");
}

function submitData(){
    vehicle_id = $('#vehicle_id').val();
    route_id = $('#route_id').val();
    var result = $.get( "GTFS-Realtime/update.php", { "lat": lat, "lon": lon, "vehicle_id": vehicle_id, "route_id": route_id } );
    console.log(result);
}

function updateAndSend(){
    updateLocation();
    submitData();
}
