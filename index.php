
<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Weather App</title>
      <!-- Google Fonts -->
      <link href="https://fonts.googleapis.com/css2?family=Alkatra&family=Delicious+Handrawn&family=Poppins:wght@600&family=Quicksand:wght@600&family=Roboto:wght@300&family=Sen:wght@700&display=swap" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    <body>
      <div class="wrapper">
        <div class="container">
          <div class="search-container">
            <input
              type="text"
              placeholder="Enter a city name"
              id="city"
              value="Aylesbury Vale"
            />
            <button id="search-btn">Search</button>
          </div>
          <h2 id="City"></h2>
          <h4 class="weather"></h4>
          <h4 class="desc" id="Condition"></h4>
          <img id="Icon">
          <h1 id="Temperature"> &#176;</h1>
          <div class="w-container">
            <div class="wind">
              <h4 class="title">Wind</h4>
              <h4 class="info" id="Wind speed"></h4>
            </div>
            <div class="humid">
               <h4 class="title">Humidity</h4>
               <h4 class="info" id="Humidity"></h4>  
            </div>
          </div>

        </div>
      </div>
        
  <div class= "mm">
  <?php
  if (isset($_GET['submit'])) {
    $city = $_GET['city'];
  } else {
    $city = "Aylesbury Vale";
  }

  $key = "6dd80c5f627ea7355262a9a7430e6155";
  $url = "https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${key}&units=metric";

  // Make API request and parse JSON response
  $response = file_get_contents($url);
  $data = json_decode($response, true);

  if (!$data) {
    // Handle API error
    die("Error: Failed to retrieve data from OpenWeatherMap API.");
  }
    
  // Extract relevant weather data
  $city_name = $data['name'];
  $weather_condition = $data['weather'][0]['main'];
  $icon = $data['weather'][0]['icon'];
  $temperature = $data['main']['temp'];
  $pressure = $data['main']['pressure'];
  $humidity = $data['main']['humidity'];
  $wind_speed = $data['wind']['speed'];
  $day_of_week = date('l', $data['dt']);

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "isa";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Check if data for the current hour is already present in database
  $sql = "SELECT * FROM weatherdata WHERE `city`='$city_name' AND DATE(`date`) = CURDATE()";

  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    // Update existing row with latest weather data
    $sql = "UPDATE weatherdata SET `dayy`='$day_of_week', `wind`='$wind_speed', `humidity`='$humidity', `temperature`='$temperature', `weathercondition`='$weather_condition', `weathericon`='$icon', `pressure`='$pressure' WHERE `city`='$city_name' AND `Date`= DATE_FORMAT(NOW(), '%Y-%m-%d %H:00:00')";
  } else {
    // Insert new row with current weather data
    $sql = "INSERT INTO weatherdata (`dayy`,`wind`, `humidity`, `temperature`, `Date`, `weathercondition`, `weathericon`, `pressure`, `city`)
          VALUES ('$day_of_week','$wind_speed', '$humidity', '$temperature', NOW(), '$weather_condition', '$icon', '$pressure', '$city_name')";
  }

  mysqli_query($conn, $sql);

  // Retrieve latest weather data from database
  $sql = "SELECT * FROM weatherdata WHERE `city`='$city_name' ORDER BY `date` DESC LIMIT 7";
  $result = mysqli_query($conn, $sql);

  echo "<table border='1' cellspacing='0'>";
  echo "<tr>";
  echo "<th>Day</th>";
  echo "<th>Date</th>";
  echo "<th>City</th>";
  echo "<th>Pressure</th>";
  echo "<th>Condition</th>";
  echo "<th>Icon</th>";
  echo "<th>Temperature</th>";
  echo "<th>Humidity</th>";
  echo "<th>Wind Speed</th>";
  echo "</tr>";
  while ($row = mysqli_fetch_assoc($result)) {
    $datetime = date('Y-m-d H:i:s', strtotime($row['Date']));
    $date = date('Y-m-d', strtotime($row['Date']));
    $time = date('H:i:s', strtotime($row['Date']));
    $day = date('l', strtotime($row['Date'])); // Get day of the week
    $condition = $row['weathercondition'];
    $icon = $row['weathericon'];
    $temperature = $row['temperature'];
    $humidity = $row['humidity'];
    $wind_speed = $row['wind'];
    $city = $row['city'];
    $pressure = $row['pressure'];

    echo "<tr>";
    echo "<td>{$day}</td>";
    echo "<td>{$date}</td>";
    echo "<td>{$city}</td>";
    echo "<td>{$pressure}Hg</td>";
    echo "<td>{$condition}</td>";
    echo "<td><img src='http://openweathermap.org/img/w/{$icon}.png'></td>";
    echo "<td>{$temperature}°C</td>";
    echo "<td>{$humidity}%</td>";
    echo "<td>{$wind_speed} m/s</td>";
    echo "</tr>";
  }
  echo "</table>";

  // Close database connection
  mysqli_close($conn);
  ?>
  </div>  
      <script src="script.js">
        
      </script>
    </body>
  </html>