<!DOCTYPE html>

<html>

    <!-- CSS and SCRIPT includes -->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <script src="https://maps.googleapis.com/maps/api/js"></script>


    <?php require_once "load.php"; ?>

    <table border="1" style="width:100%" id="main">

        <tr>
            <th colspan="2">LoL Location</th>
        </tr>
        <tr>
            <td id="nav">
                <div>
                    <form id="form_new_user">
                        Summoner Name: <input type="text" name="summoner_name" id="summoner_name">
                        <br>
                        Location: <input type="text" name="location" id="location">
                        <br>
                        <button id="summoner_submit">SUBMIT</button>
                    </form>
                </div>
                <div id="loc">
                <p>Location</p>
                <table id="loc">
                    <tr>
                        <td><a href="#">Country</a></td>
                        <td><a href="#">State</a></td>
                        <td><a href="#">County</a></td>
                    </tr>
                    <tr>
                        <td id="c">US</td>
                        <td id="s">States</td>
                        <td id="cy">County</td>
                    </tr>
                    <tr>
                        <td id="c">Canada</td>
                    </tr>
                    <tr>
                        <td id="c">South America</td>
                    </tr>
                    <tr>
                        <td id="c">Europe</td>
                    </tr>
                    <tr>
                        <td id="c">Africa</td>
                    </tr>
                    <tr>
                        <td id="c">Asia</td>
                    </tr>
                    <tr>
                        <td id="c">Australia</td>
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
    var mapCanvas, mapOptions, map;
    $(function(){
        mapCanvas = document.getElementById("map");
        mapOptions = {center: new google.maps.LatLng(38.8, -79.5), zoom: 4};
        map = new google.maps.Map(mapCanvas, mapOptions);
    });

    // prevent page reload
    $("#new_user_form").submit(function(e){
        e.preventDefault();
    });

    $("#summoner_submit").on( "click", function() {

        $.post( "new_user.php",
            {
                summoner_name: $("#summoner_name").val(),
                location: $("#location").val()
            }
        ).done(function( data ) {
            console.log( "Data Loaded: " + data );
        });
    });
</script>

</html>
