<!DOCTYPE html>

<html>

    <!-- CSS and SCRIPT includes -->
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <script src="https://maps.googleapis.com/maps/api/js"></script>


    <?php require_once "load.php"; ?>

    <table border="1" style="width:100%">

        <tr>
            <th colspan="2">LoL Location</th>
        </tr>
        <tr>
            <td id="nav">
                <div>
                    <form action="new_user.php" method="post">
                        Summoner Name: <input type="text" name="summoner_name">
                        <br>
                        Location: <input type="text" name="location">
                        <br>
                        <button type="submit">SUBMIT</button>
                    </form>
                </div>
            </td>
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
