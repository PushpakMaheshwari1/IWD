<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
  header("location: login.php");
  exit;
}

$weather = '';
$error = '';

if (isset($_GET['submit'])) {
  if (empty($_GET['city'])) {
    $error = "Your input field is empty";
  } else {
    $city = $_GET['city'];
    $apiKey = "acde350bbb4cc456bc8f8e44f9e265c4"; // Replace with your actual API key

    $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=" . $apiKey;

    $apiData = file_get_contents($apiUrl);

    if ($apiData) {
      $weatherData = json_decode($apiData, true);

      if ($weatherData['cod'] == 200) {
        $tempCelsius = $weatherData['main']['temp'] - 273.15;
        $weather = "<b>" . $weatherData['name'] . ", " . $weatherData['sys']['country'] . ":</b><br>";
        $weather .= "Temperature: " . intval($tempCelsius) . "&deg; C<br>";
        $weather .= "Weather Condition: " . $weatherData['weather'][0]['description'] . "<br>";
        $weather .= "Atmospheric Pressure: " . $weatherData['main']['pressure'] . " hPa<br>";
        $weather .= "Wind Speed: " . $weatherData['wind']['speed'] . " meter/sec<br>";
        $weather .= "Cloudiness: " . $weatherData['clouds']['all'] . " %<br>";
        $sunriseTime = date("g:i a", $weatherData['sys']['sunrise']);
        $weather .= "Sunrise: " . $sunriseTime . "<br>";
        $weather .= "Current Time: " . date("F j, Y, g:i a") . "<br>";
      } else {
        $error = "Unable to fetch weather data for '$city'. Please try again.";
      }
    } else {
      $error = "Not a valid name";
    }
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Authentication Authentication-System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@1,300&family=Poppins&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
</head>

<style>
  body {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    background-image: url(weather.jpeg);
    color: white;
    background-size: cover;
    background-attachment: fixed;
  }

  .textGlobal{
    color: blue;
    font-family: 'Montserrat';
    font-weight: 800;
  }

  .container {
    text-align: center;
    justify-content: center;
    align-items: center;
    width: 500px;
    margin: auto;
    margin-top: 150px;
  }

  input {
    width: 350px;
    padding: 5px;
  }
</style>

<body>
  <?php include_once("partials/_nav.php") ?>

  <div class="container">
    <h1 class="textGlobal">Search Global Weather</h1>
    <form action="" method="GET">
      <p><label for="city">Enter your city name</label></p>
      <p><input type="text" name="city" id="city" placeholder="City Name"></p>
      <button type="submit" name="submit" class="btn btn-success">Submit Now</button>
      <div class="output my-3">
        <?php
        if (!empty($weather)) {
          echo '<div class="alert alert-success" role="alert">' . $weather . '</div>';
        }
        if (!empty($error)) {
          echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
        }
        ?>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>
