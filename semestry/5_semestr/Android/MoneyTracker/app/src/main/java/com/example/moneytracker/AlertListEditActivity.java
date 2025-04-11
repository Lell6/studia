package com.example.moneytracker;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;

import com.example.moneytracker.DAOs.AlertListDAO;
import com.example.moneytracker.DAOs.IncomeOutcomeCategoryDAO;
import com.example.moneytracker.pre_models.AlertList;
import com.example.moneytracker.pre_models.IncomeOutcomeCategory;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

public class AlertListEditActivity extends AppCompatActivity {
    DatabaseHelper databaseHelper;
    AlertListDAO alertListDAO;
    IncomeOutcomeCategoryDAO incomeOutcomeCategoryDAO;
    int alertId = -1;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_alert_list_edit);

        databaseHelper = new DatabaseHelper(this);
        alertListDAO = new AlertListDAO(databaseHelper.getWritableDb());
        incomeOutcomeCategoryDAO = new IncomeOutcomeCategoryDAO(databaseHelper.getWritableDb());

        readAlertCategoriesList();
        setAlertListTypeList();

        Intent intent = getIntent();
        int id = intent.getIntExtra("id", -1);

        Button addNew = findViewById(R.id.addNewMoney);
        TextView title = findViewById(R.id.title);
        if (id != -1) {
            alertId = id;
            setInputValuesFromAlert();
            addNew.setText("Zmień");
            title.setText("Zmień alert");
        } else {
            Calendar calendar = Calendar.getInstance();
            SimpleDateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy");

            EditText date = findViewById(R.id.dateInput);
            date.setText(dateFormat.format(calendar.getTime()));

            addNew.setText("Dodaj");
            title.setText("Dodaj alert");
        }

        Button goBackButton = findViewById(R.id.goBack);
        goBackButton.setOnClickListener(v -> finish());

        addNew.setOnClickListener(v -> setNewAlert());
    }

    public void setInputValuesFromAlert() {
        AlertList alert = alertListDAO.getAlertListEntry(alertId);

        EditText nameInput = findViewById(R.id.nameInput);
        EditText priceInput = findViewById(R.id.priceInput);
        EditText dateInput = findViewById(R.id.dateInput);

        Spinner moneyCategorySelect = findViewById(R.id.moneyCathegorySelect);

        nameInput.setText(alert.getName());
        priceInput.setText(String.valueOf(alert.getMoneyBorderAmount()));
        dateInput.setText(alert.getStartDate());

        IncomeOutcomeCategory category = incomeOutcomeCategoryDAO.getIncomeOutcomeCategoryEntryByName(alert.getCategoryToListen());
        ArrayAdapter<String> spinnerAdapter = (ArrayAdapter<String>) moneyCategorySelect.getAdapter();

        for (int i = 0; i < spinnerAdapter.getCount(); i++) {
            if (spinnerAdapter.getItem(i).equals(category.getName())) {
                moneyCategorySelect.setSelection(i);
                break;
            }
        }
    }

    public AlertList getAlertDataFromInputs() {
        EditText nameInput = findViewById(R.id.nameInput);
        EditText priceInput = findViewById(R.id.priceInput);
        EditText dateInput = findViewById(R.id.dateInput);

        Spinner alertCategorySelect = findViewById(R.id.moneyCathegorySelect);
        Spinner alertTypeSelect = findViewById(R.id.type);

        String name = nameInput.getText().toString().trim();
        String price = priceInput.getText().toString().trim();
        String date = dateInput.getText().toString().trim();

        String selectedCategory = alertCategorySelect.getSelectedItem() != null
                ? alertCategorySelect.getSelectedItem().toString()
                : "";
        String selectedType = alertTypeSelect.getSelectedItem() != null
                ? alertTypeSelect.getSelectedItem().toString()
                : "";

        if (name.isEmpty()) {
            Toast.makeText(this, "Nazwa nie może być pusta", Toast.LENGTH_LONG).show();
            return null;
        }

        double priceAsDouble = 0.00;
        try {
            priceAsDouble = Double.parseDouble(price);
            if (price.contains(".") && price.split("\\.")[1].length() > 2) {
                Toast.makeText(this, "Kwota musi mieć 2 miejsca po przecinku", Toast.LENGTH_LONG).show();
                return null;
            }
        } catch (NumberFormatException e) {
            Toast.makeText(this, "Wprowadzono nie liczbę", Toast.LENGTH_LONG).show();
            return null;
        }

        SimpleDateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy");
        try {
            Date inputDate = dateFormat.parse(date);

            Calendar calendar = Calendar.getInstance();
            calendar.setTime(new Date());
            calendar.set(Calendar.HOUR_OF_DAY, 0);
            calendar.set(Calendar.MINUTE, 0);
            calendar.set(Calendar.SECOND, 0);
            calendar.set(Calendar.MILLISECOND, 0);
            Date currentDate = calendar.getTime();

            if (inputDate.before(currentDate)) {
                Toast.makeText(this, "Data nie moze być wcześniejsza niż dzisiaj", Toast.LENGTH_LONG).show();
                return null;
            }
        } catch (ParseException e) {
            Toast.makeText(this, "Nieprawidłowy format daty, musi być dd/MM/yyyy", Toast.LENGTH_LONG).show();
            return null;
        }

        if (selectedCategory.isEmpty()) {
            Toast.makeText(this, "Kategoria musi być wybrana", Toast.LENGTH_LONG).show();
            return null;
        }

        if (selectedType.isEmpty()) {
            Toast.makeText(this, "Typ musi być wybrany", Toast.LENGTH_LONG).show();
            return null;
        }

        return new AlertList(
                0,
                name,
                selectedCategory,
                selectedType,
                date,
                priceAsDouble
        );
    }

    public void readAlertCategoriesList() {
        List<IncomeOutcomeCategory> categories = incomeOutcomeCategoryDAO.getAllIncomeOutcomeCategories();
        if (categories == null || categories.isEmpty()) {
            Toast.makeText(this, "Brak kategorii - należy dodać nową", Toast.LENGTH_SHORT).show();
            return;
        }

        List<String> categoryNames = new ArrayList<>();
        for (IncomeOutcomeCategory category : categories) {
            categoryNames.add(category.getName());
        }

        Spinner moneyCategorySelect = findViewById(R.id.moneyCathegorySelect);
        ArrayAdapter<String> adapter = new ArrayAdapter<>(this,
                android.R.layout.simple_spinner_item, categoryNames);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        moneyCategorySelect.setAdapter(adapter);
    }

    public void setNewAlert() {
        AlertList alert = getAlertDataFromInputs();
        if (alert == null) {
            Toast.makeText(this, "Nieprawidłowo wypełniony element", Toast.LENGTH_LONG).show();
            return;
        }

        if (alertId == -1) {
            alertListDAO.addEntryToAlertList(alert);
            Toast.makeText(this, "Alert został dodany pomyślnie", Toast.LENGTH_LONG).show();
        } else {
            alertListDAO.updateAlertListEntry(alertId, alert);
            Toast.makeText(this, "Alert został zmieniony pomyślnie", Toast.LENGTH_LONG).show();
        }

        finish();
    }

    public void setAlertListTypeList() {
        List<String> list = new ArrayList<>();
        list.add("Tak");
        list.add("Nie");

        Spinner moneyTypeSelect = findViewById(R.id.type);
        ArrayAdapter<String> adapter = new ArrayAdapter<>(
                this,
                android.R.layout.simple_spinner_item,
                list
        );

        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        moneyTypeSelect.setAdapter(adapter);
    }
}
