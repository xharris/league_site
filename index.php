<!DOCTYPE html>

<html>

    <!-- CSS and SCRIPT includes -->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/container_user_list.css">
    <script src="https://maps.googleapis.com/maps/api/js"></script>

    <?php
        require_once "load.php";

        $cities = $DB->getCities();

    ?>

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
                        <td><a href="#" id="country">Country</a></td>
                        <td><a href="#" id="state">State</a></td>
                        <td><a href="#" id="county">County</a></td>
                    </tr>
                    <tr><td><input type="text" list="CountryList" id="EnterCountry"></td></tr>
                    <tr><td><input type="text" list="StateList" id="EnterState"></td></tr>
                    <form id="EnterCountry">
                    <tr><input type="text" list="CountryList"></tr>
                    </form>
<td class="country">

                    <tr><td class="county" colspan="3">County</td></tr>

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

<?php
    $cities_str = '[';

    foreach ($cities as $city) {
        $cities_str .= '"'.$city.'",';
    }
    $cities_str .= ']';
 ?>

<script>
    var mapCanvas, mapOptions, map, geocoder;
    var user_name, addr_suggest, long, lat;
    var cities;

    $(function(){
        mapCanvas = document.getElementById("map");
        map = new google.maps.Map(mapCanvas);
        geocoder = new google.maps.Geocoder;

        cities = <?php echo $cities_str; ?>;


      // add marker for cities with players
      for (city in cities) {
        geocoder.geocode( { 'address': cities[city]}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                var infowindow = new google.maps.InfoWindow(
                    { content: '<b>'+city+'</b>',
                      size: new google.maps.Size(150,50)
                    });

                var marker = new google.maps.Marker({
                    position: results[0].geometry.location,
                    map: map,
                    title:cities[city]
                });
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                });

              } else {
                alert("No results found");
              }
            } else {
              alert("Geocode was not successful for the following reason: " + status);
            }
          });
      }

      // Get the user's location
    if(navigator.geolocation) {
      browserSupportFlag = true;
      navigator.geolocation.getCurrentPosition(function(position) {
        initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
        map.setCenter(initialLocation);
        map.setZoom(10);

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
                if (response != '') {
                    user_name = response;

                    $(".container_new_user").toggleClass("hidden");
                }
            }
        });

        return false;
    });

    $("#EnterCountry").keypress(function(event)
    {
        if(event.which == 13){
            if(event.target.value == "United States"){
            console.log(event);
            $(".state").hide();
            $(".county").hide();
            $(".country").show();
        }}
    });

    $("#USA").on("click", function()
    {
        $(".county").hide();
        $(".country").hide();
        $(".state").show();
    });

    $("#county").on("click", function()
    {
        $(".state").hide();
        $(".country").hide();
        $(".county").show();
    });



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

<datalist id="CountryList">
    <option value="United States"><option value="Canada"><option value="South America"><option value="Europe">
    <option value="Africa"><<option value="Asia"><option value="Australia"></datalist>

<datalist id="StateList">
    <option value="Alabama"><option value="Alaska"><option value="Arizona"><option value="arkansas">
    <option value="california"><option value="colorado"><option value="connecticut">
    <option value="delaware"><option value="district Of Columbia">
    <option value="federated States Of Micronesia"><option value="florida"><option value="georgia">
    <option value="hawaii"><option value="idaho"><option value="illinois"><option value="indiana">
    <option value="iowa"><option value="kansas"><option value="kentucky"><option value="louisiana">
    <option value="maine"><option value="maryland"><option value="massachusetts"><option value="michigan">
    <option value="minnesota"><option value="mississippi"><option value="missouri"><option value="montana">
    <option value="nebraska"><option value="nevada"><option value="new Hampshire"><option value="new Jersey">
    <option value="new Mexico"><option value="new York"><option value="north Carolina">
    <option value="north Dakota"><option value="ohio"><option value="oklahoma"><option value="oregon">
    <option value="pennsylvania"><option value="puerto Rico<option value="rhode Island">
    <option value="south Carolina"><option value="south Dakota"><option value="tennessee">
    <option value="texas"><option value="utah"><option value="vermont"><option value="virgin Islands">
    <option value="virginia"><option value="washington"><option value="west Virginia">
    <option value="wisconsin"><option value="wyoming"><\datalist>

</html>
