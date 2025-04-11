package com.example.weatherreport;

import android.os.Handler;
import android.os.Looper;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class CityWeather {
    public int id;
    public String dateTime;
    public String currTemp;
    public String tempMin;
    public String tempMax;
    public String humidity;
    public String weatherNameMain;
    public String weatherIconUrl;
    public byte[] weatherIcon;

    protected CityWeatherActivity activity;
    protected Handler mainThreadHandler;

    protected void setImage() {
        mainThreadHandler.post(() -> activity.updateSmallImage(weatherIcon, id));
    }

    protected void addIcon(String iconUrl) {
        ExecutorService executorService;
        executorService = Executors.newSingleThreadExecutor();

        executorService.execute(() -> {
            try {
                URL urlObject = new URL(iconUrl);
                HttpURLConnection connection = (HttpURLConnection) urlObject.openConnection();
                connection.setRequestMethod("GET");

                int responseCode = connection.getResponseCode();
                System.out.println("Image URL: " + iconUrl);

                if (responseCode == HttpURLConnection.HTTP_OK) {
                    InputStream inputStream = connection.getInputStream();
                    ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream();
                    byte[] buffer = new byte[2048];
                    int bytesRead;

                    while ((bytesRead = inputStream.read(buffer)) != -1) {
                        byteArrayOutputStream.write(buffer, 0, bytesRead);
                    }

                    weatherIcon = byteArrayOutputStream.toByteArray();
                    setImage();
                }
            }
            catch (IOException exception) {
                exception.printStackTrace();
            }
        });
    }

    public CityWeather(JSONObject weather, CityWeatherActivity activity, int id) {
        this.id = id;
        this.mainThreadHandler = new Handler(Looper.getMainLooper());
        this.activity = activity;

        try {
            JSONObject weatherPart;
            dateTime = weather.getString("dt_txt");

            weatherPart = weather.getJSONObject("main");

            float temp = Float.parseFloat(weatherPart.getString("temp"));
            float tempMinValue = Float.parseFloat(weatherPart.getString("temp_min"));
            float tempMaxValue = Float.parseFloat(weatherPart.getString("temp_max"));

            int roundedTemp = Math.round(temp);
            int roundedTempMin = Math.round(tempMinValue);
            int roundedTempMax = Math.round(tempMaxValue);

            currTemp = roundedTemp + "°C";
            tempMin = roundedTempMin + "°C";
            tempMax = roundedTempMax + "°C";

            humidity = weatherPart.getString("humidity") + "%";

            JSONArray weatherArray = weather.getJSONArray("weather");
            JSONObject weatherDetails = weatherArray.getJSONObject(0);
            weatherNameMain = weatherDetails.getString("main");
            weatherIconUrl = "https://openweathermap.org/img/wn/"
                    + weatherDetails.getString("icon")
                    + "@4x.png";

            addIcon(weatherIconUrl);
        }
        catch (JSONException exception) {
            exception.printStackTrace();
        }
    }
}
