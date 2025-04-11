package com.example.weatherreport;

import java.io.Serializable;
import java.util.List;

public class CityObject implements Serializable {
    String name;
    String apiLink;

    public CurrentCityWeather currentWeather;
    public List<CityWeather> weatherList;

    public CityObject(String name) {
        String apiKey = "1d826409e0d332088cf55ec2f5c6082e";
        setName(name);
        setApiLink("https://api.openweathermap.org/data/2.5/forecast?q=" + name.replace(" ", "+") + "&cnt=24&units=metric&lang=pl&appid=" + apiKey);
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getApiLink() {
        return apiLink;
    }

    public void setApiLink(String link) {
        this.apiLink = link;
    }
}
