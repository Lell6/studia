package com.example.moneytracker;

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
import android.app.AlertDialog;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;

import com.example.moneytracker.DAOs.IncomeOutcomeCategoryDAO;
import com.example.moneytracker.pre_models.IncomeOutcomeCategory;

import java.util.ArrayList;
import java.util.List;

public class IncomeOutcomeCategoryMainActivity extends AppCompatActivity {
    DatabaseHelper databaseHelper;
    IncomeOutcomeCategoryDAO incomeOutcomeCategoryDAO;
    ListView listContainer;

    @Override
    public void onResume() {
        super.onResume();
        readIncomeOutcomeCategoryList();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_income_outcome_category_main);

        databaseHelper = new DatabaseHelper(this);
        incomeOutcomeCategoryDAO = new IncomeOutcomeCategoryDAO(databaseHelper.getWritableDb());
        listContainer = findViewById(R.id.listView);

        readIncomeOutcomeCategoryList();

        Button addNewCategory = findViewById(R.id.addNew);
        addNewCategory.setOnClickListener(v -> openAddCategoryActivity());

        Button goBack = findViewById(R.id.goBack);
        goBack.setOnClickListener(v -> finish());
    }

    public void readIncomeOutcomeCategoryList() {
        List<IncomeOutcomeCategory> categoryList = incomeOutcomeCategoryDAO.getAllIncomeOutcomeCategories();
        if (categoryList == null || categoryList.isEmpty()) {
            List<String> emptyMessageList = new ArrayList<>();
            emptyMessageList.add("Brak kategorii dla wpływów / wydatków");

            ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, emptyMessageList);

            listContainer.setAdapter(adapter);
            return;
        }

        ArrayAdapter<IncomeOutcomeCategory> adapter = new ArrayAdapter<IncomeOutcomeCategory>(this, android.R.layout.simple_list_item_1, categoryList) {
            @Override
            public View getView(int position, View convertView, ViewGroup parent) {
                if (convertView == null) {
                    convertView = LayoutInflater.from(getContext()).inflate(android.R.layout.simple_list_item_1, parent, false);
                }

                IncomeOutcomeCategory item = getItem(position);
                TextView textView = convertView.findViewById(android.R.id.text1);
                textView.setText(item.toString());

                textView.setOnClickListener(v -> openEditCategoryActivity(v, item.getId()));

                textView.setOnLongClickListener(v -> {
                    new AlertDialog.Builder(IncomeOutcomeCategoryMainActivity.this)
                            .setTitle("Usuwanie")
                            .setMessage("Chcesz usunąć tę kategorię?" + " (" + item.getName() + ")")
                            .setPositiveButton("Tak", (dialog, which) -> {
                                int categoryId = item.getId();
                                incomeOutcomeCategoryDAO.deleteIncomeOutcomeCategoryEntry(categoryId);

                                Toast.makeText(IncomeOutcomeCategoryMainActivity.this, "Kategoria została usunięta", Toast.LENGTH_SHORT).show();
                                readIncomeOutcomeCategoryList();
                            })
                            .setNegativeButton("Nie", (dialog, which) -> {
                                dialog.dismiss();
                            })
                            .show();

                    return true;
                });

                return convertView;
            }
        };

        listContainer.setAdapter(adapter);
    }

    public void openAddCategoryActivity() {
        Intent intent = new Intent(IncomeOutcomeCategoryMainActivity.this, IncomeOutcomeCategoryEditActivity.class);
        startActivity(intent);
    }

    public void openEditCategoryActivity(View v, int categoryId) {
        Intent intent = new Intent(IncomeOutcomeCategoryMainActivity.this, IncomeOutcomeCategoryEditActivity.class);
        intent.putExtra("id", categoryId);
        startActivity(intent);
    }
}