<?php
// function to geocode address using Google Maps API
function geocode($address) {
    $address = urlencode($address);
    $apiKey = "AIzaSyAmo6ZfKZZ4YeYvaGwpmaom_-ZmtYiWT74";

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";

    $resp_json = file_get_contents($url);
    $resp = json_decode($resp_json, true);

    if($resp['status'] == 'OK') {
        // get the important data
        $latitude  = $resp['results'][0]['geometry']['location']['lat'];
        $longitude = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];

        if($latitude && $longitude && $formatted_address) {
            // return the data
            return array(
                $latitude,
                $longitude,
                $formatted_address
            );
        } else {
            return false;
        }
    } else {
        return false;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Google Maps Geocoding Example with PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        input[type=text], input[type=submit] {
            padding: 10px;
            font-size: 16px;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            width: 80%;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h2>Google Maps Geocoding Example</h2>
<form method="post" action="">
    <input type="text" name="address" placeholder="Enter address" size="50">
    <input type="submit" name="submit" value="Geocode!">
</form>

<?php
if(isset($_POST['submit'])) {
    $address = $_POST['address'];

    if(!empty($address)) {
        $data = geocode($address);

        if($data) {
            list($latitude, $longitude, $formatted_address) = $data;

            echo "<div class='result'>";
            echo "<strong>Formatted Address:</strong> {$formatted_address}<br>";
            echo "<strong>Latitude:</strong> {$latitude}<br>";
            echo "<strong>Longitude:</strong> {$longitude}";
            echo "</div>";
        } else {
            echo "<div class='result'>No location found for the address.</div>";
        }
    } else {
        echo "<div class='result'>Please enter an address.</div>";
    }
}
?>

</body>
</html>
