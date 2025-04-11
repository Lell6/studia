package com.example.weatherreport;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class MainActivity extends AppCompatActivity {
    private RecyclerView recyclerView;
    private CityAdapter cityAdapter;
    private List<CityObject> cityList;
    private ExecutorService executorService;
    private Handler mainThreadHandler;

    private String getLastOpenedCity() {
        String city = "";
        SharedPreferences sharedPref = this.getPreferences(Context.MODE_PRIVATE);
        city = sharedPref.getString("lastCity", "Nysa");

        return city;
    }

    @Override
    protected void onResume() {
        super.onResume();
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_main);

        recyclerView = findViewById(R.id.cityListContainer);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));

        cityList = new ArrayList<>();
        cityList.add(new CityObject("New York"));
        cityList.add(new CityObject("Los Angeles"));
        cityList.add(new CityObject("Chicago"));
        cityList.add(new CityObject("San Francisco"));
        cityList.add(new CityObject("Tokyo"));
        cityList.add(new CityObject("Paris"));
        cityList.add(new CityObject("Nysa"));
        cityList.add(new CityObject("Warszawa"));
        cityList.add(new CityObject("Kraków"));
        cityList.add(new CityObject("Poznań"));
        cityList.add(new CityObject("London"));
        cityList.add(new CityObject("Sydney"));
        cityList.add(new CityObject("Cape Town"));
        cityList.add(new CityObject("Rio de Janeiro"));

        cityAdapter = new CityAdapter(cityList, this);
        recyclerView.setAdapter(cityAdapter);
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        this.executorService = Executors.newSingleThreadExecutor();
        this.mainThreadHandler = new Handler(Looper.getMainLooper());

        String lastCity = getLastOpenedCity();
        CityObject city = new CityObject(lastCity);

        executorService.execute(() -> {
            try {
                String url = city.getApiLink();

                URL urlObject = new URL(url);
                HttpURLConnection connection = (HttpURLConnection) urlObject.openConnection();
                connection.setRequestMethod("GET");
                int responseCode = connection.getResponseCode();
                //System.out.println("API URL: " + url);

                if (responseCode == HttpURLConnection.HTTP_OK) {
                    BufferedReader buffer = new BufferedReader(new InputStreamReader(connection.getInputStream()));
                    StringBuilder wholeResponse = new StringBuilder();
                    String singleResponse;

                    while ((singleResponse = buffer.readLine()) != null) {
                        wholeResponse.append(singleResponse);
                    }
                    buffer.close();
                    String wholeStringResponse = wholeResponse.toString();
                    mainThreadHandler.post(() -> openWeatherForCity(wholeStringResponse, city));
                }
            }
            catch (IOException exception) {
                exception.printStackTrace();
            }
        });
    }

    private JSONArray getWeatherFromString(String weatherData) {
        JSONArray separatedCityWeather = new JSONArray();

        try {
            JSONObject wholeCityWeather = new JSONObject(weatherData);
            JSONArray cityWeather = wholeCityWeather.getJSONArray("list");

            int currTime = 12;

            for (int i = 0; i < cityWeather.length(); i++) {
                JSONObject singleCityWeather = cityWeather.getJSONObject(i);
                String[] datetime = singleCityWeather.getString("dt_txt").split(" ");
                int time = Integer.parseInt(datetime[1].split(":")[0]);

                if (i == 0) {
                    currTime = time;
                    separatedCityWeather.put(singleCityWeather);
                }

                if (time == currTime - 3) {
                    separatedCityWeather.put(singleCityWeather);
                }
            }
        }
        catch (JSONException exception) {
            exception.printStackTrace();
        }

        return separatedCityWeather;
    }

    public void openWeatherForCity(String weatherData, CityObject city) {
        SharedPreferences sharedPref = this.getPreferences(Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPref.edit();
        editor.putString("lastCity", city.getName());
        editor.apply();

        JSONArray weather = getWeatherFromString(weatherData);

        Bundle bundle = new Bundle();
        bundle.putSerializable("city", city);
        bundle.putString("weather", weather.toString());

        Intent intent = new Intent(this, CityWeatherActivity.class);
        intent.putExtras(bundle);
        startActivity(intent);
    }

    public void addCity(View view) {
        EditText newCity = findViewById(R.id.editTextText);
        cityList.add(new CityObject(newCity.getText().toString()));
        cityAdapter.notifyDataSetChanged();
        Toast.makeText(this, "City " + newCity.getText().toString() + " was added", Toast.LENGTH_SHORT).show();
    }
}