package com.example.weatherreport;

import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;

import org.json.JSONArray;
import org.json.JSONException;

import java.util.ArrayList;

public class CityWeatherActivity extends AppCompatActivity {
    CityObject city;

    private void setWeather(String weather) {
        try {
            JSONArray weatherObject = new JSONArray(weather);
            city.currentWeather = new CurrentCityWeather(weatherObject.getJSONObject(0), this);
            city.currentWeather.addIcon(city.currentWeather.weatherIconUrl);
            city.weatherList = new ArrayList<>();

            for (int i = 1; i < weatherObject.length(); i++) {
                city.weatherList.add(new CityWeather(weatherObject.getJSONObject(i), this, i-1));
                city.weatherList.get(i-1).addIcon(city.weatherList.get(i-1).weatherIconUrl);
            }
        }
        catch (JSONException exception) {
            exception.printStackTrace();
        }
    }

    private void updateCurrentWeather() {
        TextView name = findViewById(R.id.name);
        TextView date = findViewById(R.id.currDateTime);
        TextView currTemp = findViewById(R.id.currTemp);
        TextView currTempFeel = findViewById(R.id.currTempFeel);
        TextView tempMinMax = findViewById(R.id.tempMinMax);

        TextView description = findViewById(R.id.weatherDescription);

        TextView humidity = findViewById(R.id.humidity);
        TextView pressure = findViewById(R.id.pressure);

        TextView wind = findViewById(R.id.wind);

        name.setText(city.getName());
        date.setText(city.currentWeather.dateTime);
        currTemp.setText(city.currentWeather.currTemp);
        currTempFeel.setText("Odczuwalna: " + city.currentWeather.currTempFeel);

        String minMax = city.currentWeather.tempMax + " / " + city.currentWeather.tempMin;
        tempMinMax.setText(minMax);

        humidity.setText(city.currentWeather.humidity);
        pressure.setText(city.currentWeather.pressure);

        String windText = city.currentWeather.windSpeed + "\n" + city.currentWeather.windType;
        wind.setText(windText);

        description.setText(city.currentWeather.weatherNameDesc);
    }

    private void updateNextWeather(CityWeather cityWeather, int number) {
        int tempId = getResources().getIdentifier("temp" + number, "id", getPackageName());
        int dateId = getResources().getIdentifier("date" + number, "id", getPackageName());

        TextView temp = findViewById(tempId);
        TextView date = findViewById(dateId);

        temp.setText(cityWeather.currTemp);
        date.setText(cityWeather.dateTime);
    }

    private void updateUI() {
        updateCurrentWeather();

        for (int i = 0; i < city.weatherList.size(); i++) {
            updateNextWeather(city.weatherList.get(i), i);
        }
    }

    public void updateImage(byte[] icon) {
        ImageView weatherConditionBig = findViewById(R.id.weatherIconBig);
        Bitmap bitmap = BitmapFactory.decodeByteArray(icon, 0, icon.length);

        weatherConditionBig.setImageBitmap(bitmap);
    }

    public void updateSmallImage(byte[] icon, int number) {
        int imageId = getResources().getIdentifier("imageView" + number, "id", getPackageName());

        ImageView image = findViewById(imageId);
        Bitmap bitmap = BitmapFactory.decodeByteArray(icon, 0, icon.length);

        image.setImageBitmap(bitmap);
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_city_weather);

        Intent intent = getIntent();
        city = (CityObject) intent.getSerializableExtra("city");
        setWeather(intent.getStringExtra("weather"));
        updateUI();
    }

    public void goBack(View view) {
        finish();
    }
}