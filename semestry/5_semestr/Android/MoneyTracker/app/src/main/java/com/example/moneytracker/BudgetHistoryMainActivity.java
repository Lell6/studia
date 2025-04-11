package com.example.moneytracker;

import android.os.Bundle;
import android.widget.ListView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.example.moneytracker.DAOs.AlertListDAO;
import com.example.moneytracker.DAOs.IncomeOutcomeListDAO;
import com.example.moneytracker.pre_models.AlertList;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

public class BudgetHistoryMainActivity extends AppCompatActivity {
    DatabaseHelper databaseHelper;
    AlertListDAO alertListDAO;
    IncomeOutcomeListDAO incomeOutcomeListDAO;
    ListView listContainer;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_budget_history_main);
        databaseHelper = new DatabaseHelper(this);
        alertListDAO = new AlertListDAO(databaseHelper.getWritableDb());
        incomeOutcomeListDAO = new IncomeOutcomeListDAO(databaseHelper.getWritableDb());
        checkAlerts();
    }

    public void checkAlerts() {
        List<AlertList> alertList = alertListDAO.getAllAlertLists();

        if (alertList == null || alertList.isEmpty()) {
            Toast.makeText(this, "Brak alertów - dodaj nowy (Alerty -> dodaj)", Toast.LENGTH_LONG).show();
            return;
        }

        for (AlertList entry : alertList) {
            String alertDate = entry.getStartDate();

            if (entry.getIsActive().equals("Tak")) {
                Calendar calendar = Calendar.getInstance();
                SimpleDateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy");

                try {
                    Date alertDateParsed = dateFormat.parse(alertDate);

                    Calendar alertDateCalendar = Calendar.getInstance();
                    alertDateCalendar.setTime(alertDateParsed);
                    alertDateCalendar.add(Calendar.DAY_OF_YEAR, 30);

                    Date currentDate = calendar.getTime();

                    if (currentDate.after(alertDateCalendar.getTime())) {
                        alertDateCalendar.add(Calendar.DAY_OF_YEAR, 30);
                        String newAlertDate = dateFormat.format(alertDateCalendar.getTime());
                        entry.setStartDate(newAlertDate);
                    }
                    else {
                        double moneyAccumulated = incomeOutcomeListDAO.getIncomeOutcomeEntryMoneyAccumulatedByCategoryName(entry.getCategoryToListen());
                        if (moneyAccumulated >= entry.getMoneyBorderAmount()) {
                            Toast.makeText(this, "Alert: " + entry.getName() + " Przekroczono limit wydatków", Toast.LENGTH_LONG).show();
                        }
                    }
                } catch (ParseException e) {
                    e.printStackTrace();
                }
            }
        }
    }
}