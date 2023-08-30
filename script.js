let result = document.getElementById("result");
let searchBtn = document.getElementById("search-btn");
let cityRef = document.getElementById("city");

// Function to fetch weather details from API and display them
let getWeather = async () => {
  let cityValue = cityRef.value;
  // If input field is empty
  if (cityValue.length == 0) {
    result.innerHTML = `<h3 class="msg">Please enter a city name</h3>`;
  }
  // If input field is NOT empty
  else {
    let key = "6dd80c5f627ea7355262a9a7430e6155";
    let url = `https://api.openweathermap.org/data/2.5/weather?q=${cityValue}&appid=${key}&units=metric`;
    // Clear the input field
    cityRef.value = "";
    try {
      const resp = await fetch(url);
      const data = await resp.json();
      console.log(data);
      console.log(data.weather[0].icon);
      console.log(data.weather[0].main);
      console.log(data.weather[0].description);
      console.log(data.name);
      console.log(data.main.temp_min);
      console.log(data.main.temp_max);
      result.innerHTML = `
        <h2 id="City">${data.name}</h2>
        <h4 class="weather">${data.weather[0].main}</h4>
        <h4 class="desc" id="Condition">${data.weather[0].description}</h4>
        <img src="https://openweathermap.org/img/w/${data.weather[0].icon}.png" id="Icon">
        <h1 id="Temperature">${data.main.temp} &#176;</h1>
        <div class="w-container">
            <div class="wind">
              <h4 class="title">Wind</h4>
              <h4 class="info" id="Wind speed">${data.wind.speed}Km/H</h4>
            </div>
            <div class="humid">
               <h4 class="title">Humidity</h4>
               <h4 class="info" id="Humidity">${data.main.humidity}%</h4>  
            </div>
        </div>
      `;
    } catch (error) {
      result.innerHTML = `<h3 class="msg">City not found</h3>`;
    }
  }
};



document.addEventListener("keydown", function(event) {
  if (event.key === "Enter") {
    // Code to execute when Enter key is pressed
    getWeather(); // Call the function to get weather information
  }
});

// Event listener for the "click" event on the search button
searchBtn.addEventListener("click", getWeather);

// Event listener for the "load" event of the window
window.addEventListener("load", getWeather);

// Event triggered when the document is unloaded or refreshed
window.addEventListener("unload", function() {
  cityRef.value = "Aylesbury Vale"; // Set default city as "Aylesbury Vale"
});

let cityValue = "Aylesbury Vale";
let key = "6dd80c5f627ea7355262a9a7430e6155"
let date = new Date();
let dayOfWeek = new Intl.DateTimeFormat("en-US", { weekday: "long" }).format(date);
function defaultWeather() {
  fetch(`https://api.openweathermap.org/data/2.5/weather?q=${cityValue}&appid=${key}&units=metric`)
    .then(response => response.json())
    .then(data => {
      weatherInfo(data);
      console.log(data);
      
      document.getElementById('City').textContent = data.name;
      localStorage.setItem("City", data.name);
      document.getElementById('Temperature').textContent = data.main.temp + '°C';
      localStorage.setItem("Temperature", data.main.temp + '°C');
      document.getElementById('Condition').textContent = data.weather[0].description;
      localStorage.setItem("Condition", data.weather[0].description);
      document.getElementById('Wind speed').textContent = data.wind.speed + ' km/h';
      localStorage.setItem("Wind speed", data.wind.speed + ' km/h');
      document.getElementById('Humidity').textContent = data.main.humidity + '%';
      localStorage.setItem("Humidity", data.main.humidity + '%');
      document.getElementById('Pressure').textContent = data.main.pressure + ' hPa';
      localStorage.setItem("Pressure", data.main.pressure + ' hPa');
      document.getElementById('Icon').src = `http://openweathermap.org/img/w/${data.weather[0].icon}.png`;
      localStorage.setItem("Icon", `http://openweathermap.org/img/w/${data.weather[0].icon}.png`);
      document.getElementById('Day').textContent = dayOfWeek;
      localStorage.setItem("Day", dayOfWeek);
      document.getElementById('Date').textContent = date.toDateString();
      localStorage.setItem("Date", date.toDateString());
    })
    .catch(error => {
      console.log('Error fetching default weather data:', error);
      document.getElementById('offline').style.display = 'block';
      document.getElementById("City").textContent = localStorage.getItem("City");
      document.getElementById("Temperature").textContent = localStorage.getItem("Temperature");
      document.getElementById("Condition").textContent = localStorage.getItem("Condition");
      document.getElementById("Wind speed").textContent = localStorage.getItem("Wind speed");
      document.getElementById("Humidity").textContent = localStorage.getItem("Humidity");
      document.getElementById("Pressure").textContent = localStorage.getItem("Pressure");
      document.getElementById("Day").textContent = localStorage.getItem("Day");
      document.getElementById("Date").textContent = localStorage.getItem("Date");
      document.getElementById("Icon").src = localStorage.getItem("Icon");
    });
}

defaultWeather();