package com.example.weatherreport;

import static java.lang.Math.floor;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class CurrentCityWeather extends CityWeather {
    public String currTempFeel;
    public String pressure;
    public String weatherNameDesc;
    public String windSpeed;
    public String windType;

    @Override
    protected void setImage() {
        mainThreadHandler.post(() -> activity.updateImage(weatherIcon));
    }

    public CurrentCityWeather(JSONObject weather, CityWeatherActivity activity) {
        super(weather, activity, -1);

        String[] compassDirections = {
                "N", "NNE", "NE", "ENE",
                "E", "ESE", "SE", "SSE",
                "S", "SSW", "SW", "WSW",
                "W", "WNW", "NW", "NNW"
        };

        try {
            JSONObject weatherPart = weather.getJSONObject("main");

            float temp = Float.parseFloat(weatherPart.getString("feels_like"));
            int roundedTemp = Math.round(temp);
            currTempFeel = roundedTemp + "°C";

            pressure = weatherPart.getString("pressure") + "hPa";

            JSONArray weatherArray = weather.getJSONArray("weather");
            JSONObject weatherDetails = weatherArray.getJSONObject(0);
            weatherNameDesc = weatherDetails.getString("description");

            weatherPart = weather.getJSONObject("wind");
            windSpeed = weatherPart.getString("speed") + "m/s";

            float windDegree = Float.parseFloat(weatherPart.getString("deg"));
            int directionIndex = (int) floor(windDegree / 22.5);
            windType = compassDirections[directionIndex];

            addIcon(weatherIconUrl);
        }
        catch (JSONException exception) {
            exception.printStackTrace();
        }
    }
}
