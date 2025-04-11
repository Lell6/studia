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

import com.example.moneytracker.DAOs.BudgetHistoryDAO;
import com.example.moneytracker.DAOs.IncomeOutcomeCategoryDAO;
import com.example.moneytracker.DAOs.IncomeOutcomeListDAO;
import com.example.moneytracker.pre_models.BudgetHistory;
import com.example.moneytracker.pre_models.IncomeOutcomeCategory;
import com.example.moneytracker.pre_models.IncomeOutcomeList;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

public class IncomeOutcomeEditActivity extends AppCompatActivity {
    DatabaseHelper databaseHelper;
    IncomeOutcomeListDAO incomeOutcomeListDAO;
    IncomeOutcomeCategoryDAO incomeOutcomeCategoryDAO;
    BudgetHistoryDAO budgetHistoryDAO;
    int entryId = -1;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_income_outcome_edit);

        databaseHelper = new DatabaseHelper(this);
        incomeOutcomeListDAO = new IncomeOutcomeListDAO(databaseHelper.getWritableDb());
        incomeOutcomeCategoryDAO = new IncomeOutcomeCategoryDAO(databaseHelper.getWritableDb());
        budgetHistoryDAO = new BudgetHistoryDAO(databaseHelper.getWritableDb());

        readIncomeOutComeCategoriesList();
        setIncomeOutcomeTypeList();
        setIncomeOutcomeRepeatList();
        setIncomeOutcomeStatusList();

        Intent intent = getIntent();
        int id = intent.getIntExtra("id", -1);

        Button addNew = findViewById(R.id.addNewMoney);
        TextView title = findViewById(R.id.title);
        if (id != -1) {
            entryId = id;
            setInputValuesFromEntry();
            addNew.setText("Zmień");
            title.setText("Zmień wpływ / wydatek");
        }
        else {
            Calendar calendar = Calendar.getInstance();
            SimpleDateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy");

            EditText date = findViewById(R.id.dateInput);
            date.setText(dateFormat.format(calendar.getTime()));
            addNew.setText("Dodaj");
            title.setText("Dodaj wpływ / wydatek");
        }

        Button goBackButton = findViewById(R.id.goBack);
        goBackButton.setOnClickListener(v -> finish());

        addNew.setOnClickListener(v -> setNewEntryInIncomeOutcome());
    }

    public void setInputValuesFromEntry() {
        IncomeOutcomeList entry = incomeOutcomeListDAO.getIncomeOutcomeEntry(entryId);

        EditText nameInput = findViewById(R.id.nameInput);
        EditText priceInput = findViewById(R.id.priceInput);
        EditText dateInput = findViewById(R.id.dateInput);

        Spinner moneyCategorySelect = findViewById(R.id.moneyCathegorySelect);
        Spinner moneyType = findViewById(R.id.type);
        Spinner moneyRepeat = findViewById(R.id.moneyRepeat);

        nameInput.setText(entry.getName());
        priceInput.setText("" + entry.getMoneyAmount());
        dateInput.setText(entry.getDate());

        if (entry.getType().equals("Wydatek")) {
            moneyType.setSelection(0);
        } else if (entry.getType().equals("Wpływ")) {
            moneyType.setSelection(1);
        }

        if (entry.getRepeat().equals("Stały")) {
            moneyRepeat.setSelection(0);
        } else if (entry.getRepeat().equals("Jednorazowy")) {
            moneyRepeat.setSelection(1);
        }

        IncomeOutcomeCategory category = incomeOutcomeCategoryDAO.getIncomeOutcomeCategoryEntryByName(entry.getCategory());
        ArrayAdapter<String> spinnerAdapter = (ArrayAdapter<String>) moneyCategorySelect.getAdapter();

        for (int i = 0; i < spinnerAdapter.getCount(); i++) {
            if (spinnerAdapter.getItem(i).equals(category.getName())) {
                moneyCategorySelect.setSelection(i);
                break;
            }
        }
    }

    public IncomeOutcomeList getDataFromInputs() {
        EditText nameInput = findViewById(R.id.nameInput);
        EditText priceInput = findViewById(R.id.priceInput);
        EditText dateInput = findViewById(R.id.dateInput);

        Spinner moneyCategorySelect = findViewById(R.id.moneyCathegorySelect);
        Spinner moneyType = findViewById(R.id.type);
        Spinner moneyRepeat = findViewById(R.id.moneyRepeat);
        Spinner moneyStatus = findViewById(R.id.moneyStatus);

        String name = nameInput.getText().toString().trim();
        String price = priceInput.getText().toString().trim();
        String date = dateInput.getText().toString().trim();

        String selectedMoneyCategory = moneyCategorySelect.getSelectedItem() != null
                ? moneyCategorySelect.getSelectedItem().toString()
                : "";
        String selectedMoneyType = moneyType.getSelectedItem() != null
                ? moneyType.getSelectedItem().toString()
                : "";
        String selectedRepeatOption = moneyRepeat.getSelectedItem() != null
                ? moneyRepeat.getSelectedItem().toString()
                : "";
        String selectedStatusOption = moneyStatus.getSelectedItem() != null
                ? moneyRepeat.getSelectedItem().toString()
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
                Toast.makeText(this, "Data nie moze być wcześniej niż dzisiaj", Toast.LENGTH_LONG).show();
                return null;
            }
        } catch (ParseException e) {
            Toast.makeText(this, "Nieprawidłowy format daty, musi być dd/MM/yyyy", Toast.LENGTH_LONG).show();
            return null;
        }

        if (selectedMoneyCategory.isEmpty() || selectedMoneyType.isEmpty() || selectedRepeatOption.isEmpty() || selectedStatusOption.isEmpty()) {
            Toast.makeText(this, "Typ, kategoria, status muszą być wybrane", Toast.LENGTH_LONG).show();
            return null;
        }

        IncomeOutcomeCategory selectedCategory = incomeOutcomeCategoryDAO.getIncomeOutcomeCategoryEntryByName(selectedMoneyCategory);

        if (selectedCategory != null && !selectedMoneyType.equals(selectedCategory.getType())) {
            Toast.makeText(this, "Typ kategorii nie pasuje do wybranego typu transakcji", Toast.LENGTH_LONG).show();
            return null;
        }

        return new IncomeOutcomeList(
                0,
                name,
                selectedMoneyType,
                selectedRepeatOption,
                selectedMoneyCategory,
                selectedStatusOption,
                date,
                priceAsDouble
        );
    }

    public void readIncomeOutComeCategoriesList() {
        List<IncomeOutcomeCategory> list = incomeOutcomeCategoryDAO.getAllIncomeOutcomeCategories();
        if (list == null || list.isEmpty()) {
            Toast.makeText(this, "Brak kategorij - należy dodać nową", Toast.LENGTH_SHORT).show();
            return;
        }

        List<String> categoryNames = new ArrayList<>();
        for (IncomeOutcomeCategory category : list) {
            categoryNames.add(category.getName());
        }

        Spinner moneyCategorySelect = findViewById(R.id.moneyCathegorySelect);
        ArrayAdapter<String> adapter = new ArrayAdapter<>(
                this,
                android.R.layout.simple_spinner_item,
                categoryNames
        );

        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        moneyCategorySelect.setAdapter(adapter);
    }

    public void setIncomeOutcomeTypeList() {
        List<String> list = new ArrayList<>();
        list.add("Wydatek");
        list.add("Wpływ");

        Spinner moneyTypeSelect = findViewById(R.id.type);
        ArrayAdapter<String> adapter = new ArrayAdapter<>(
                this,
                android.R.layout.simple_spinner_item,
                list
        );

        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        moneyTypeSelect.setAdapter(adapter);
    }

    public void setIncomeOutcomeRepeatList() {
        List<String> list = new ArrayList<>();
        list.add("Stały");
        list.add("Jednorazowy");

        Spinner moneyRepeatSelect = findViewById(R.id.moneyRepeat);
        ArrayAdapter<String> adapter = new ArrayAdapter<>(
                this,
                android.R.layout.simple_spinner_item,
                list
        );

        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        moneyRepeatSelect.setAdapter(adapter);
    }

    public void setIncomeOutcomeStatusList() {
        List<String> list = new ArrayList<>();
        list.add("Aktywny");
        list.add("Archiwum");

        Spinner moneyRepeatSelect = findViewById(R.id.moneyStatus);
        ArrayAdapter<String> adapter = new ArrayAdapter<>(
                this,
                android.R.layout.simple_spinner_item,
                list
        );

        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        moneyRepeatSelect.setAdapter(adapter);
    }

    public void setNewEntryInIncomeOutcome() {
        IncomeOutcomeList entry = getDataFromInputs();
        if (entry == null) {
            Toast.makeText(this, "Nieprawidłowo wypełniony element", Toast.LENGTH_LONG).show();
            return;
        }

        if (entryId == -1) {
            int newEntryId = (int) incomeOutcomeListDAO.addEntryToIncomeOutcomeList(entry);

            if (entry.getRepeat().equals("Jednorazowy")) {
                BudgetHistory historyEntry = new BudgetHistory(0, newEntryId, entry.getMoneyAmount(), entry.getName(), entry.getType());
                budgetHistoryDAO.addEntryToBudgetHistory(historyEntry);
            }

            Toast.makeText(this, "Wpływ / wydatek został dodany pomyślnie", Toast.LENGTH_LONG).show();
        }
        else {
            incomeOutcomeListDAO.updateIncomeOutcomeEntry(entryId, entry);
            Toast.makeText(this, "Wpływ / wydatek został zmieniony pomyślnie", Toast.LENGTH_LONG).show();
        }

        finish();
    }
}