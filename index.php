<!DOCTYPE html>

<html>

    <!-- CSS and SCRIPT includes -->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/container_user_list.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.2/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.1.2/material.min.js"></script>

    <!--script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script-->

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-Cg6p9pV6LdgjuQDWJ3iSULR_rq6XC_I&libraries=places&callback=initAutocomplete"
       async defer></script>


    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <?php
        require_once "load.php";
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

                </div>
    <div id="locationField"><input id="autocomplete" placeholder="Enter your address" onFocus="geolocate()" type="text"></input></div>
<!-- NOT USED -->
<input class="field" id="street_number" disabled="true"></input>
<input class="field" id="route" disabled="true"></input>
<input class="field" id="postal_code" disabled="true"></input>
<!-- -->
<input class="field" id="country" disabled="true" readonly></input>
<input class="field" id="administrative_area_level_1" disabled="true" readonly></input>
<input class="field" id="locality" disabled="true" readonly></input>

    <table id="SummonerFinder">
        <tr><th>Summoners</th></tr>
        <tr><td>Hi</td></tr>
        <tr><td>Yo</td></tr>
    </table>
</div>



    <!-- Documentation: url="https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform" -->
   <script>

     var placeSearch, autocomplete;
     var componentForm = {
       street_number: 'short_name',
       route: 'long_name',
       locality: 'long_name',
       administrative_area_level_1: 'short_name',
       country: 'long_name',
       postal_code: 'short_name'
     };

     function initAutocomplete() {
       // Create the autocomplete object, restricting the search to geographical
       // location types.
       autocomplete = new google.maps.places.Autocomplete(
           /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
           {types: ['geocode']});

       // When the user selects an address from the dropdown, populate the address
       // fields in the form.
       autocomplete.addListener('place_changed', fillInAddress);
     }

function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

     // Bias the autocomplete object to the user's geographical location,
     // as supplied by the browser's 'navigator.geolocation' object.
     function geolocate() {
       if (navigator.geolocation) {
         navigator.geolocation.getCurrentPosition(function(position) {
           var geolocation = {
             lat: position.coords.latitude,
             lng: position.coords.longitude
           };
           var circle = new google.maps.Circle({
             center: geolocation,
             radius: position.coords.accuracy
           });
           autocomplete.setBounds(circle.getBounds());
         });
       }
     }
    </script>


            <div id="loc"></div>
            </td>
            <td id = "map">
                <div id="map" style="width:100%;height:100%">Please Reload Page, as there was an error loading the first time.</div>
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
    var user_info, addr_suggest, long, lat;
    var cities, users;
    var markers = [];

    $(function(){
        mapCanvas = document.getElementById("map");
        map = new google.maps.Map(mapCanvas);
        geocoder = new google.maps.Geocoder;

        var infowindow, marker;


  var infowindow = new google.maps.InfoWindow();

  var marker, i;

  refreshMapMarkers();
  refreshUserList();
    doUserThings();

      // Get the user's location
    if(navigator.geolocation) {
      browserSupportFlag = true;
      navigator.geolocation.getCurrentPosition(function(position) {
        initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
        map.setCenter(initialLocation);
        map.setZoom(5);

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

    function refreshMapMarkers() {
        $.ajax({
            url:"php/getCities.php",
            success: function (response) {
                // set up
                if (response != '') {
                    cities = JSON.parse(response);

                    for(var m in markers){
                        markers[m].setMap(null);
                    }

                    for (i = 0; i < cities.length; i++) {
                        addMarker(cities[i].name, cities[i].population, map)
                    }
                }
            }
        });
    }

    function refreshUserList() {
        $.ajax({
            url:"php/getUsers.php",
            success: function (response) {
                // set up
                if (response != '') {
                    users = JSON.parse(response);

                    $(".container_user_list").empty();

                    for(var u in users){
                        $(".container_user_list").append("\
                            "+users[u].summoner_name+"<br>\
                        ");
                    }
                }
            }
        });
    }


    function doUserThings(){
                    for(var u in users){
                        console.log(users[u]);
                    }
    }

    function calcDistance(aLat,aLng,bLat,bLng){

    }

    // Adds a marker to the map.
    function addMarker(location, label, map) {
      geocoder.geocode({'address': location}, function(results, status) {

          var coords = {
              lat:results[0].geometry.location.lat(),
              lng:results[0].geometry.location.lng()
          };

          marker = new google.maps.Marker({
            position: coords,
            label: label,
            map: map,
            infowindow: new google.maps.InfoWindow({
                            content: location
                        })
          });

          markers.push(marker);

        google.maps.event.addListener(marker, 'click', function() {
          this.infowindow.open(map, this);
        });
     });
    }

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
                    user_info = response;

                    $(".container_new_user").toggleClass("hidden");

                    refreshMapMarkers();
                    refreshUserList();
                }
            }
        });

        return false;
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

</html>
