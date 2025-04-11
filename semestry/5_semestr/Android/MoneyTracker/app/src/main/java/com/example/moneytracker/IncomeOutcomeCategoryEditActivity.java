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
import com.example.moneytracker.DAOs.IncomeOutcomeListDAO;
import com.example.moneytracker.pre_models.AlertList;
import com.example.moneytracker.pre_models.IncomeOutcomeCategory;
import com.example.moneytracker.pre_models.IncomeOutcomeList;

import org.w3c.dom.Text;

import java.util.ArrayList;
import java.util.List;

public class IncomeOutcomeCategoryEditActivity extends AppCompatActivity {
    DatabaseHelper databaseHelper;
    IncomeOutcomeCategoryDAO incomeOutcomeCategoryDAO;
    IncomeOutcomeListDAO incomeOutcomeListDAO;
    AlertListDAO alertListDAO;
    int entryId = -1;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_income_outcome_category_edit);

        databaseHelper = new DatabaseHelper(this);
        incomeOutcomeCategoryDAO = new IncomeOutcomeCategoryDAO(databaseHelper.getWritableDb());
        incomeOutcomeListDAO = new IncomeOutcomeListDAO(databaseHelper.getWritableDb());
        alertListDAO = new AlertListDAO(databaseHelper.getWritableDb());

        setIncomeOutcomeTypeList();

        Intent intent = getIntent();
        Button addNew = findViewById(R.id.addNew);
        TextView title = findViewById(R.id.title);
        int id = intent.getIntExtra("id", -1);

        if (id != -1) {
            entryId = id;
            setInputValuesFromEntry();
            addNew.setText("Zmień");
            title.setText("Zmień kategorię");
        }
        else {
            addNew.setText("Dodaj");
            title.setText("Dodaj kategorię");
        }

        Button goBackButton = findViewById(R.id.goBack);
        goBackButton.setOnClickListener(v -> finish());

        addNew.setOnClickListener(v -> setNewEntryInIncomeOutcomeCategory());
    }

    public IncomeOutcomeCategory getDataFromInputs() {
        EditText nameInput = findViewById(R.id.nameInput);
        Spinner type = findViewById(R.id.type);

        String name = nameInput.getText().toString().trim();
        String selectedType = type.getSelectedItem() != null
                ? type.getSelectedItem().toString()
                : "";

        if (name.isEmpty()) {
            Toast.makeText(this, "Nazwa nie może być pusta", Toast.LENGTH_LONG).show();
            return null;
        }

        IncomeOutcomeCategory foundedEntry = incomeOutcomeCategoryDAO.getIncomeOutcomeCategoryEntryByName(name);
        if (foundedEntry != null) {
            Toast.makeText(this, "Kategoria o takiej nazwie już istnieje", Toast.LENGTH_LONG).show();
            return null;
        }

        if (selectedType.isEmpty()) {
            Toast.makeText(this, "Typ musi być wybrany", Toast.LENGTH_LONG).show();
            return null;
        }
        return new IncomeOutcomeCategory(
                0,
                name,
                selectedType
        );
    }

    public void setIncomeOutcomeTypeList() {
        List<String> list = new ArrayList<>();
        list.add("Wydatek");
        list.add("Wpływ");

        Spinner type = findViewById(R.id.type);
        ArrayAdapter<String> adapter = new ArrayAdapter<>(
                this,
                android.R.layout.simple_spinner_item,
                list
        );

        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        type.setAdapter(adapter);
    }

    public void setInputValuesFromEntry() {
        IncomeOutcomeCategory entry = incomeOutcomeCategoryDAO.getIncomeOutcomeCategoryEntry(entryId);

        EditText nameInput = findViewById(R.id.nameInput);
        Spinner type = findViewById(R.id.type);

        nameInput.setText(entry.getName());

        if (entry.getType().equals("Wydatek")) {
            type.setSelection(0);
        }
        if (entry.getType().equals("Wpływ")) {
            type.setSelection(1);
        }
    }

    public void setNewEntryInIncomeOutcomeCategory() {
        IncomeOutcomeCategory entry = getDataFromInputs();
        if (entry == null) {
            Toast.makeText(this, "Nieprawidłowo wypełniony element", Toast.LENGTH_SHORT).show();
            return;
        }

        if (entryId == -1) {
            incomeOutcomeCategoryDAO.addEntryToIncomeOutcomeCategoryList(entry);
            Toast.makeText(this, "Kategoria została dodana pomyślnie", Toast.LENGTH_SHORT).show();
        }
        else {
            List<IncomeOutcomeList> incomeOurcomeList = incomeOutcomeListDAO.getAllIncomeOutcomes();
            List<AlertList> alertList = alertListDAO.getAllAlertLists();

            for (IncomeOutcomeList incomeOutcomeEntry : incomeOurcomeList) {
                if (incomeOutcomeEntry.getCategory().equals(incomeOutcomeCategoryDAO.getIncomeOutcomeCategoryEntry(entryId).getName())) {
                    incomeOutcomeEntry.setCategory(entry.getName());
                    incomeOutcomeListDAO.updateIncomeOutcomeEntry(incomeOutcomeEntry.getId(), incomeOutcomeEntry);
                }
            }

            for (AlertList alertEntry : alertList) {
                if (alertEntry.getCategoryToListen().equals(incomeOutcomeCategoryDAO.getIncomeOutcomeCategoryEntry(entryId).getName())) {
                    alertEntry.setCategoryToListen(entry.getName());
                    alertListDAO.updateAlertListEntry(alertEntry.getId(), alertEntry);
                }
            }

            incomeOutcomeCategoryDAO.updateIncomeOutcomeCategoryEntry(entryId, entry);
            Toast.makeText(this, "Kategoria została zmieniona pomyślnie", Toast.LENGTH_SHORT).show();
        }

        finish();
    }
}