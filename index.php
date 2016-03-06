<!DOCTYPE html>

<html>

    <!-- CSS and SCRIPT includes -->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/container.css">
    <link rel="stylesheet" type="text/css" href="css/form_new_user.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.2/material.blue_grey-orange.min.css" />
    <script defer src="https://code.getmdl.io/1.1.2/material.min.js"></script>

    <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>

    <!--script src="https://maps.googleapis.com/maps/api/js?sensor=false&amplibraries=places&callback=initAutocomplete"
       async defer></script-->


    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <?php
        require_once "load.php";
    ?>

    <table border="1" style="width:100%" id="main">

        <tr>
            <th colspan="2">LoLcator</th>
        </tr>
        <tr>
            <td id="nav">
                <button id="btn_new_user" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">Add me!</button>

                <div class="container_new_user hidden">
                    <form id="form_new_user">

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="summoner_name">
                            <label class="mdl-textfield__label" for="summoner_name">Summoner Name</label>
                        </div>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="location">
                            <label class="mdl-textfield__label" for="location">Location</label>
                        </div>

                        <button id="summoner_submit"  class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent">SUBMIT</button>
                    </form>
                </div>

                <div class="container_user_list">
                    <p>Players</p>
                    <div class="user_list">

                    </div>
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

    var API_KEY = '800eb4db-8f56-48ee-8f7f-aefefeca5769';

    $(function(){
        mapCanvas = document.getElementById("map");
        map = new google.maps.Map(mapCanvas);
        geocoder = new google.maps.Geocoder;

        var infowindow, marker;


  var infowindow = new google.maps.InfoWindow();

  var marker, i;

  refreshMapMarkers();
  refreshUserList();

  var found = false;
  for (var u in users) {
      if (getCooke("users") && users[u].name == getCookie("users")) {
          var user_loc = new google.maps.LatLng(users[u].latitude,users[u].longitude);
          map.setCenter(user_loc);
          map.setZoom(15);
          found = true;
      }
  }

      // Get the user's location
    if(navigator.geolocation) {
      browserSupportFlag = true;
      navigator.geolocation.getCurrentPosition(function(position) {
        initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
        map.setCenter(initialLocation);
        map.setZoom(4);

        if (!found) {
            geocoder.geocode({'address': "USA"}, function(results, status) {
                initialLocation = new google.maps.LatLng(results[0].geometry.location.lat(),results[0].geometry.location.lng());
                map.setCenter(initialLocation);
            });
        }

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

    var input = document.getElementById('location');

    var ac = new google.maps.places.Autocomplete(input);
    ac.bindTo('bounds', map);

    });

    function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

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

                    $(".container_user_list > .user_list").empty();

                    for(var u in users){
                        console.log(users[u])
                        getLevel(users[u].summoner_name, function(summ_name,user_level){

                            is_me = '';
                            if (summ_name == getCookie("user")) {
                                is_me = ' class="current_user" ';
                                getStuff(summ_name, function(summ_name,user_level){})
                            }

                            $(".container_user_list > .user_list").append("\
                              <div"+is_me+">"+summ_name+" ("+user_level+")</div>\
                            ");
                        });

                    }
                }
            }
        });
    }

    function calcDistance(aLat,aLng,bLat,bLng){

    }

    function moveMap() {}

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

    function getLevel(username,callback){
        call = 'https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/' + username + '?api_key='+API_KEY


        $.ajax({
            url: call,
            type: 'GET',
            dataType: 'json',
            data: {

            },
            success: function (json) {
                var SUMMONER_NAME_NOSPACES = username.replace(" ", "");

                SUMMONER_NAME_NOSPACES = SUMMONER_NAME_NOSPACES.toLowerCase().trim();

                summonerLevel = json[SUMMONER_NAME_NOSPACES].summonerLevel;
                summonerID = json[SUMMONER_NAME_NOSPACES].id;

                callback(username,summonerLevel);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("error getting Summoner data!");
            }
        });
    }

    function getStuff(username,callback){
        call = 'https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/' + username + '?api_key='+API_KEY


        $.ajax({
            url: call,
            type: 'GET',
            dataType: 'json',
            data: {

            },
            success: function (json) {
                var SUMMONER_NAME_NOSPACES = username.replace(" ", "");

                SUMMONER_NAME_NOSPACES = SUMMONER_NAME_NOSPACES.toLowerCase().trim();

                summonerLevel = json[SUMMONER_NAME_NOSPACES].summonerLevel;
                summonerID = json[SUMMONER_NAME_NOSPACES].id;

                callback(username,summonerLevel);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("error getting Summoner data!");
            }
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

                    console.log(getCookie("user"));

                    refreshMapMarkers();
                    refreshUserList();
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
            $("#EnterState").show();
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
    <option value="USA"><option value="Canada"><option value="South America"><option value="Europe">
    <option value="Africa"><<option value="Asia"><option value="Australia"></datalist>

<datalist id="StateList">
    <option value="Alabama"><option value="Alaska"><option value="Arizona"><option value="Arkansas">
    <option value="California"><option value="Colorado"><option value="Connecticut">
    <option value="Delaware"><option value="District Of Columbia">
    <option value="Federated States Of Micronesia"><option value="Florida"><option value="Georgia">
    <option value="Hawaii"><option value="Idaho"><option value="Illinois"><option value="Indiana">
    <option value="Iowa"><option value="Kansas"><option value="Kentucky"><option value="Louisiana">
    <option value="Maine"><option value="Maryland"><option value="Massachusetts"><option value="Michigan">
    <option value="Minnesota"><option value="Mississippi"><option value="Missouri"><option value="Montana">
    <option value="Nebraska"><option value="Nevada"><option value="New Hampshire"><option value="New Jersey">
    <option value="New Mexico"><option value="New York"><option value="North Carolina">
    <option value="North Dakota"><option value="Ohio"><option value="Oklahoma"><option value="Oregon">
    <option value="Pennsylvania"><option value="Puerto Rico"><option value="Rhode Island">
    <option value="South Carolina"><option value="South Dakota"><option value="Tennessee">
    <option value="Texas"><option value="Utah"><option value="Vermont"><option value="Virgin Islands">
    <option value="Virginia"><option value="Washington"><option value="West Virginia">
    <option value="Wisconsin"><option value="Wyoming"><\datalist>

</html>
