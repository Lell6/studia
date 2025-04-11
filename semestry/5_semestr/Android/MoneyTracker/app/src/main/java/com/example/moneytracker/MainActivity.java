package com.example.moneytracker;

import android.app.AlertDialog;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;

import com.example.moneytracker.DAOs.AlertListDAO;
import com.example.moneytracker.DAOs.BudgetHistoryDAO;
import com.example.moneytracker.DAOs.IncomeOutcomeListDAO;
import com.example.moneytracker.pre_models.AlertList;
import com.example.moneytracker.pre_models.BudgetHistory;
import com.example.moneytracker.pre_models.IncomeOutcomeCategory;
import com.example.moneytracker.pre_models.IncomeOutcomeList;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

public class MainActivity extends AppCompatActivity {
    DatabaseHelper databaseHelper;
    AlertListDAO alertListDAO;
    IncomeOutcomeListDAO incomeOutcomeListDAO;
    BudgetHistoryDAO budgetHistoryDAO;
    ListView budgetHistoryList;
    ListView alertsList;

    @Override
    protected void onResume() {
        super.onResume();
        checkAlerts();
        checkOutcomes();
        populateBudgetHistoryList();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_main);

        databaseHelper = new DatabaseHelper(this);
        alertListDAO = new AlertListDAO(databaseHelper.getWritableDb());
        incomeOutcomeListDAO = new IncomeOutcomeListDAO(databaseHelper.getWritableDb());
        budgetHistoryDAO = new BudgetHistoryDAO(databaseHelper.getWritableDb());
        budgetHistoryList = findViewById(R.id.budgetHistoryList);
        alertsList = findViewById(R.id.alertsList);
        populateBudgetHistoryList();
        checkAlerts();
        checkOutcomes();

        Button incomeOutcomeButton = findViewById(R.id.moneyButton);
        Button alertButton = findViewById(R.id.alertButton);
        Button categoryButton = findViewById(R.id.categoryButton);
        Button chartButton = findViewById(R.id.chartButton);

        incomeOutcomeButton.setOnClickListener(v -> openIncomeOutcomeActivity(v));
        categoryButton.setOnClickListener(v -> openIncomeOutcomeCategoryActivity(v));
        alertButton.setOnClickListener(v -> openAlertActivity(v));
        chartButton.setOnClickListener(v -> openChartActivity(v));
    }

    public void openIncomeOutcomeActivity(View view) {
        Intent intent = new Intent(MainActivity.this, IncomeOutcomeMainActivity.class);
        startActivity(intent);
    }

    public void openIncomeOutcomeCategoryActivity(View view) {
        Intent intent = new Intent(MainActivity.this, IncomeOutcomeCategoryMainActivity.class);
        startActivity(intent);
    }

    public void openAlertActivity(View view) {
        Intent intent = new Intent(MainActivity.this, AlertListMainActivity.class);
        startActivity(intent);
    }

    public void openChartActivity(View view) {
        Intent intent = new Intent(MainActivity.this, ChartActivity.class);
        startActivity(intent);
    }

    public void checkAlerts() {
        List<AlertList> alertList = alertListDAO.getAllAlertLists();
        List<String> alertMessages = new ArrayList<>();

        if (alertList == null || alertList.isEmpty()) {
            alertMessages.add("Brak aktywnych alertów");
        } else {
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
                        } else {
                            double moneyAccumulated = incomeOutcomeListDAO.getIncomeOutcomeEntryMoneyAccumulatedByCategoryName(entry.getCategoryToListen());

                            if (moneyAccumulated >= entry.getMoneyBorderAmount()) {
                                alertMessages.add("Alert: " + entry.getName() + " - Przekroczono limit wydatków");
                            }
                        }
                    } catch (ParseException e) {
                        e.printStackTrace();
                    }
                }
            }

            if (alertMessages.isEmpty()) {
                alertMessages.add("Brak aktywnych alertów");
            }
        }

        ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, alertMessages);
        alertsList.setAdapter(adapter);
    }

    public void checkOutcomes() {
        List<IncomeOutcomeList> list = incomeOutcomeListDAO.getAllOutcomes();
        if (list == null || list.isEmpty()) {
            return;
        }

        for (IncomeOutcomeList entry : list) {
            String entryDate = entry.getDate();
            Calendar calendar = Calendar.getInstance();
            SimpleDateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy");

            if ("Aktywny".equals(entry.getStatus())) {
                try {
                    Date entryDateParsed = dateFormat.parse(entryDate);

                    Calendar entryDateCalendar = Calendar.getInstance();
                    entryDateCalendar.setTime(entryDateParsed);
                    entryDateCalendar.add(Calendar.DAY_OF_YEAR, 30);

                    Date currentDate = calendar.getTime();

                    if (currentDate.after(entryDateCalendar.getTime())) {
                        IncomeOutcomeList newEntry = new IncomeOutcomeList();
                        newEntry.setStatus("Aktywny");
                        newEntry.setDate(dateFormat.format(entryDateCalendar.getTime()));
                        newEntry.setMoneyAmount(entry.getMoneyAmount());
                        newEntry.setCategory(entry.getCategory());

                        int newEntryId = (int) incomeOutcomeListDAO.addEntryToIncomeOutcomeList(newEntry);

                        BudgetHistory historyEntry = new BudgetHistory(0, newEntryId, newEntry.getMoneyAmount(), newEntry.getName(), newEntry.getType());
                        budgetHistoryDAO.addEntryToBudgetHistory(historyEntry);
                    }
                } catch (ParseException e) {
                    e.printStackTrace();
                }
            }
        }
    }

    public void populateBudgetHistoryList() {
        List<BudgetHistory> list = budgetHistoryDAO.getAllBudgetHistory();
        if (list == null || list.isEmpty()) {
            List<String> emptyMessageList = new ArrayList<>();
            emptyMessageList.add("Brak historii");

            ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, emptyMessageList);

            budgetHistoryList.setAdapter(adapter);
            return;
        }

        ArrayAdapter<BudgetHistory> adapter = new ArrayAdapter<BudgetHistory>(this, android.R.layout.simple_list_item_1, list) {
            @Override
            public View getView(int position, View convertView, ViewGroup parent) {
                if (convertView == null) {
                    convertView = LayoutInflater.from(getContext()).inflate(android.R.layout.simple_list_item_1, parent, false);
                }

                BudgetHistory item = getItem(position);
                TextView textView = convertView.findViewById(android.R.id.text1);
                textView.setText(item.toString());

                return convertView;
            }
        };
        budgetHistoryList.setAdapter(adapter);
    }
/*
    public void openChartActivity(View view) {
        Intent intent = new Intent(MainActivity.this, ChartMainActivity.class);
        startActivity(intent);
    }*/
}