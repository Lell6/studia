package com.example.weatherreport;
import android.graphics.Color;
import android.view.*;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.List;

import java.io.IOException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

import android.os.Handler;
import android.os.Looper;
import android.widget.Toast;

public class CityAdapter extends RecyclerView.Adapter<CityAdapter.ViewHolder> {
    private List<CityObject> cityList;
    private ExecutorService executorService;
    private Handler mainThreadHandler;
    private MainActivity activity;

    public static class ViewHolder extends RecyclerView.ViewHolder {
        private final TextView textView;

        public ViewHolder(View view) {
            super(view);
            textView = (TextView) view.findViewById(R.id.itemText);
        }

        public TextView getTextView() {
            return textView;
        }
    }

    public CityAdapter(List<CityObject> cityList, MainActivity activity) {
        this.cityList = cityList;
        this.activity = activity;
        this.executorService = Executors.newSingleThreadExecutor();
        this.mainThreadHandler = new Handler(Looper.getMainLooper());
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup viewGroup, int viewType) {
        View view = LayoutInflater.from(viewGroup.getContext())
                .inflate(R.layout.city_list_item, viewGroup, false);

        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, final int position) {
        CityObject city = cityList.get(position);

        holder.textView.setText(city.getName());

        holder.itemView.setOnLongClickListener(v -> {
            CityObject holdCity = cityList.get(position);
            Toast.makeText(activity, "City " + holdCity.getName() + " was removed", Toast.LENGTH_SHORT).show();
            cityList.remove(holdCity);
            notifyDataSetChanged();
            return true;
        });
        holder.itemView.setOnClickListener(v -> {
            holder.itemView.setBackgroundColor(Color.argb(255,184,239,228));
            CityObject clickedCity = cityList.get(position);

            executorService.execute(() -> {
                try {
                    String url = clickedCity.getApiLink();

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
                        holder.itemView.setBackgroundColor(Color.TRANSPARENT);
                        mainThreadHandler.post(() -> activity.openWeatherForCity(wholeStringResponse, clickedCity));
                    }
                }
                catch (IOException exception) {
                    exception.printStackTrace();
                }
            });
        });
    }
    @Override
    public int getItemCount() {
        return cityList.size();
    }
}