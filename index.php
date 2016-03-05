<!DOCTYPE html>

<html>

    <!-- CSS and SCRIPT includes -->
    <link rel="stylesheet" type="text/css" href="index.css">
    <script src="https://maps.googleapis.com/maps/api/js"></script>


    <?php include("load.php"); ?>

    <table border="1" style="width:100%">

        <tr>
            <th colspan="2">LoL Location</th>
        </tr>
        <tr>
            <td id="nav"><p>Left bar</p></td>
            <td>
                <div id="map" style="width:100%;height:400px">Map goes here</div>
            </td>
        </tr>
    </table>

<script>
var mapCanvas = document.getElementById("map");
var mapOptions = {center: new google.maps.LatLng(51.5, -0.2), zoom: 10};
var map = new google.maps.Map(mapCanvas, mapOptions);
</script>

</html>
