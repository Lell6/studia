package com.example.moneytracker;

import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;

import com.example.moneytracker.DAOs.IncomeOutcomeCategoryDAO;
import com.example.moneytracker.DAOs.IncomeOutcomeListDAO;
import com.example.moneytracker.pre_models.IncomeOutcomeCategory;
import com.example.moneytracker.pre_models.IncomeOutcomeList;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

public class ChartActivity extends AppCompatActivity {
    private BarChartView barChartView;
    private DatabaseHelper databaseHelper;
    IncomeOutcomeListDAO incomeOutcomeListDAO;
    IncomeOutcomeCategoryDAO incomeOutcomeCategoryDAO;
    List<IncomeOutcomeList> list;
    String category1Global;
    String category2Global;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_chart);

        databaseHelper = new DatabaseHelper(this);
        incomeOutcomeListDAO = new IncomeOutcomeListDAO(databaseHelper.getWritableDb());
        incomeOutcomeCategoryDAO = new IncomeOutcomeCategoryDAO(databaseHelper.getWritableDb());

        Spinner spinnerContainer1 = findViewById(R.id.category1);
        populateCategory(spinnerContainer1);

        Spinner spinnerContainer2 = findViewById(R.id.category2);
        populateCategory(spinnerContainer2);

        list = incomeOutcomeListDAO.getAllOutcomes();
        Button goBack = findViewById(R.id.goBack);
        goBack.setOnClickListener(v -> finish());

        Button generateChart = findViewById(R.id.generateChart);
        generateChart.setOnClickListener(v -> generateChart(v));

        Calendar calendar = Calendar.getInstance();
        SimpleDateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy");

        EditText date = findViewById(R.id.fromDate);
        date.setText(dateFormat.format(calendar.getTime()));

        date = findViewById(R.id.toDate);
        date.setText(dateFormat.format(calendar.getTime()));
    }

    public void populateCategory(Spinner category) {
        List<IncomeOutcomeCategory> list = incomeOutcomeCategoryDAO.getAllIncomeOutcomeCategories();
        if (list == null || list.isEmpty()) {
            Toast.makeText(this, "Brak kategorij - należy dodać nową", Toast.LENGTH_SHORT).show();
            return;
        }

        List<String> categoryNames = new ArrayList<>();
        //categoryNames.add("Wszystkie");
        for (IncomeOutcomeCategory categoryEntry : list) {
            categoryNames.add(categoryEntry.getName());
        }

        ArrayAdapter<String> adapter = new ArrayAdapter<>(
                this,
                android.R.layout.simple_spinner_item,
                categoryNames
        );

        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        category.setAdapter(adapter);
    }

    public void generateChart(View v) {
        List<IncomeOutcomeList> filtered = filterValues();
        if (filtered == null || filtered.isEmpty()) {
            Toast.makeText(this, "Brak wyników dla wybranego filtru", Toast.LENGTH_LONG).show();
            return;
        }

        List<String> dates = new ArrayList<>();
        List<Double> category1Sums = new ArrayList<>();
        List<Double> category2Sums = new ArrayList<>();

        for (IncomeOutcomeList entry : filtered) {
            String currentDate = entry.getDate();
            double moneyAmount = entry.getMoneyAmount();
            String currentCategory = entry.getCategory();

            int dateIndex = dates.indexOf(currentDate);
            if (dateIndex == -1) {
                dates.add(currentDate);
                category1Sums.add(currentCategory.equals(category1Global) ? moneyAmount : 0.0);
                category2Sums.add(currentCategory.equals(category2Global) ? moneyAmount : 0.0);
            } else {
                if (currentCategory.equals(category1Global)) {
                    category1Sums.set(dateIndex, category1Sums.get(dateIndex) + moneyAmount);
                }
                if (currentCategory.equals(category2Global)) {
                    category2Sums.set(dateIndex, category2Sums.get(dateIndex) + moneyAmount);
                }
            }
        }

        BarChartView barChartView = findViewById(R.id.barChartView);
        barChartView.setData(dates, category1Sums, category2Sums, category1Global, category2Global);
    }

    public List<IncomeOutcomeList> filterValues() {
        Spinner category1 = findViewById(R.id.category1);
        Spinner category2 = findViewById(R.id.category2);
        EditText fromDate = findViewById(R.id.fromDate);
        EditText toDate = findViewById(R.id.toDate);

        String selectedCategory1Option = category1.getSelectedItem() != null
                ? category1.getSelectedItem().toString()
                : "";
        String selectedCategory2Option = category2.getSelectedItem() != null
                ? category2.getSelectedItem().toString()
                : "";
        category1Global = selectedCategory1Option;
        category2Global = selectedCategory2Option;

        String fromDateValue = fromDate.getText().toString().trim();
        String toDateValue = toDate.getText().toString().trim();

        if (selectedCategory1Option.isEmpty() || selectedCategory2Option.isEmpty() || fromDateValue.isEmpty() || toDateValue.isEmpty()) {
            Toast.makeText(this, "Nie wypełniono wszystkie pola", Toast.LENGTH_SHORT).show();
            return null;
        }

        SimpleDateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy");
        Date inputFromDate;
        Date inputToDate;

        try {
            inputFromDate = dateFormat.parse(fromDateValue);
            inputToDate = dateFormat.parse(toDateValue);

            Calendar calendar = Calendar.getInstance();
            calendar.setTime(inputFromDate);
            calendar.set(Calendar.HOUR_OF_DAY, 0);
            calendar.set(Calendar.MINUTE, 0);
            calendar.set(Calendar.SECOND, 0);
            calendar.set(Calendar.MILLISECOND, 0);
            inputFromDate = calendar.getTime();

            calendar.setTime(inputToDate);
            calendar.set(Calendar.HOUR_OF_DAY, 0);
            calendar.set(Calendar.MINUTE, 0);
            calendar.set(Calendar.SECOND, 0);
            calendar.set(Calendar.MILLISECOND, 0);
            inputToDate = calendar.getTime();

        } catch (ParseException e) {
            Toast.makeText(this, "Nieprawidłowy format daty, musi być dd/MM/yyyy", Toast.LENGTH_LONG).show();
            return null;
        }

        List<IncomeOutcomeList> incomeOutcomeList = incomeOutcomeListDAO.getAllIncomeOutcomes();
        List<IncomeOutcomeList> filtered = new ArrayList<>();
        for (IncomeOutcomeList entry : incomeOutcomeList) {
            if (/*selectedCategory1Option.equals("Wszystkie") || selectedCategory2Option.equals("Wszystkie") ||*/ entry.getCategory().equals(selectedCategory1Option) || entry.getCategory().equals(selectedCategory2Option)) {
                filtered.add(entry);
            }
        }

        List<IncomeOutcomeList> filteredDate = new ArrayList<>();
        for (IncomeOutcomeList entry : filtered) {
            Date entryDate;

            try {
                entryDate = dateFormat.parse(entry.getDate());

                Calendar calendar = Calendar.getInstance();
                calendar.setTime(entryDate);
                calendar.set(Calendar.HOUR_OF_DAY, 0);
                calendar.set(Calendar.MINUTE, 0);
                calendar.set(Calendar.SECOND, 0);
                calendar.set(Calendar.MILLISECOND, 0);

                entryDate = calendar.getTime();
            } catch (ParseException e) {
                Toast.makeText(this, "Nieprawidłowy format daty, musi być dd/MM/yyyy", Toast.LENGTH_LONG).show();
                return null;
            }

            if ((entryDate.equals(inputFromDate) || entryDate.after(inputFromDate)) &&
                    (entryDate.equals(inputToDate) || entryDate.before(inputToDate))) {
                filteredDate.add(entry);
            }
        }

        return filteredDate;
    }
}