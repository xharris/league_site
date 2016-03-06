<!DOCTYPE html>

<html>

    <!-- CSS and SCRIPT includes -->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/container_user_list.css">
    <script src="https://maps.googleapis.com/maps/api/js"></script>


    <?php require_once "load.php"; ?>

    <table border="1" style="width:100%" id="main">

        <tr>
            <th colspan="2">LoL Location</th>
        </tr>
        <tr>
            <td id="nav">
                <button id="btn_new_user">Add me!</button>

                <div class="container_new_user hidden">
                    <form id="form_new_user">
                        Summoner Name: <input type="text" name="summoner_name" id="summoner_name">
                        <br>
                        Location: <input type="text" name="location" id="location">
                        <br>
                        <button id="summoner_submit">SUBMIT</button>
                    </form>
                </div>

                <div class="container_user_list">
                    <?php

                    $user_list = $DB->getUserLocations();

                    if (sizeof($user_list)) {
                        foreach ($user_list as $u => $user) {
                            echo $user['summoner_name'].'<br>';
                        }
                    }

                     ?>
                </div>

                <div id="loc"></div>
                <p>Location</p>
                <table id="loc">
                    <tr>
                        <td><a href="#" id="c">Country</a></td>
                        <td><a href="#">State</a></td>
                        <td><a href="#">County</a></td>
                    </tr>
                    <tr>
                        <td class="c">US</td>
                        <td class="s">States</td>
                        <td class="cy">County</td>
                    </tr>
                    <tr>
                        <td class="c">Canada</td>
                    </tr>
                    <tr>
                        <td class="c">South America</td>
                    </tr>
                    <tr>
                        <td class="c">Europe</td>
                    </tr>
                    <tr>
                        <td class="c">Africa</td>
                    </tr>
                    <tr>
                        <td class="c">Asia</td>
                    </tr>
                    <tr>
                        <td class="c">Australia</td>
                    </tr>
                </table>
            </td>
            <td id = "map">
                <div id="map" style="width:100%;height:100%">Map goes here</div>
            </td>
        </tr>
        <tr id="footer">
            <td colspan="2">
                <p id="footer">site design / logo &#169; 2016, Made by Summoners like you Thank You.</p>
            </td>
        </tr>
    </table>

<script>
    var mapCanvas, mapOptions, map, geocoder;
    var user_name, addr_suggest, long, lat;

    $(function(){
        mapCanvas = document.getElementById("map");
        mapOptions = {center: new google.maps.LatLng(38.8, -79.5), zoom: 4};
        map = new google.maps.Map(mapCanvas, mapOptions);
        geocoder = new google.maps.Geocoder;
    });

    // prevent page reload
    $("#new_user_form").submit(function(e){
        e.preventDefault();
    });

    // show new user form
    $("#btn_new_user").on("click",function(){
        $(".container_new_user").toggleClass("hidden");
    });

    // new user form submit
    $("#summoner_submit").on( "click", function() {

        $.ajax({
            type: 'post',
            url: 'new_user.php',
            data: {
                summoner_name: $("#summoner_name").val(),
                location: $("#location").val(),
                longitude: long,
                latitude: lat
            },
            success: function (response) {
                // set up
                user_name = response;

                $(".container_new_user").toggleClass("hidden");
            }
        });

        return false;
    });

    $("#c").on("click", function()
    {
        $(".s").toggleClass("hidden");
        $(".cy").toggleClass("hidden");
    });

    $("Country").on("click", function()
    {
        $("s").toggleClass("hidden");
        $("cy").toggleClass("hidden");
    });

    // Get the user's location
  if(navigator.geolocation) {
    browserSupportFlag = true;
    navigator.geolocation.getCurrentPosition(function(position) {
      initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
      map.setCenter(initialLocation);
      map.setZoom(15);

      geocoder.geocode({'location': initialLocation}, function(results, status) {
          // suggest it to them (lol)
          addr_suggest = results[2].formatted_address;
          $("#location").val(addr_suggest);

          long = results[0].geometry.location.lng();
          lat = results[0].geometry.location.lat();
      });

    }, function() {
      handleNoGeolocation(browserSupportFlag);
    });
  }
  // Browser doesn't support Geolocation
  else {
    browserSupportFlag = false;
    handleNoGeolocation(browserSupportFlag);
  }

  function handleNoGeolocation(errorFlag) {
    if (errorFlag == true) {
      alert("Geolocation service failed.");
      initialLocation = newyork;
    } else {
      alert("Your browser doesn't support geolocation. We've placed you in Siberia.");
      initialLocation = siberia;
    }
    map.setCenter(initialLocation);
  }
</script>

</html>
