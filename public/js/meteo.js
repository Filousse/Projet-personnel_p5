function ajaxGet(url, isJson, callback) {
    let request = new XMLHttpRequest();
    request.open("GET", url);     // On récupére le contenu du fichier avec la méthode GET
    if (isJson) {
        request.setRequestHeader("Accept", "application/json");
    }
    request.addEventListener("load", function () {
        if (request.status >= 200 && request.status < 400) {
            let data = request.responseText;
            if (isJson) {
                callback(JSON.parse(data));
            } else {
                callback(data);
            }
        } else {
            console.error(`Erreur ${request.status} ${request.statusText}`);
        }
    })
    request.addEventListener("error", function () {
        console.error("Request fail. No response from server.")
    });
    request.send(null);
};


ajaxGet("http://api.openweathermap.org/data/2.5/weather?q=Paris,fr&APPID=d0a1ab8838c4c2791bfc57be0984530b&units=metric", true, (data) => {

  const temp = data.main.temp;
  const tempMin = data.main.temp_min;
  const tempMax = data.main.temp_max;
  const humidity = data.main.humidity;
  const pressure = data.main.pressure;
  const description = data.weather[0].description;
  const wind = data.wind.speed;

  document.getElementById("temp").textContent = temp + " °" ;
  document.getElementById("tempMin").textContent = tempMin + " °";
  document.getElementById("tempMax").textContent = tempMax + " °";
  document.getElementById("humidity").textContent = humidity + " %";
  document.getElementById("pressure").textContent = pressure + " Pa";
  document.getElementById("description").textContent = description;
  document.getElementById("wind").textContent = wind + " m/s";
});
