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

import android.app.AlertDialog;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;

import com.example.moneytracker.DAOs.IncomeOutcomeListDAO;
import com.example.moneytracker.pre_models.BudgetHistory;
import com.example.moneytracker.pre_models.IncomeOutcomeCategory;
import com.example.moneytracker.pre_models.IncomeOutcomeList;

import java.util.ArrayList;
import java.util.List;

public class IncomeOutcomeMainActivity extends AppCompatActivity {
    DatabaseHelper databaseHelper;
    IncomeOutcomeListDAO incomeOutcomeListDAO;
    ListView listContainer;

    @Override
    public void onResume() {
        super.onResume();
        readIncomeOutcomeList();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_income_outcome_main);

        databaseHelper = new DatabaseHelper(this);
        incomeOutcomeListDAO = new IncomeOutcomeListDAO(databaseHelper.getWritableDb());
        listContainer = findViewById(R.id.listView);

        readIncomeOutcomeList();

        Button addNew = findViewById(R.id.addNew);
        addNew.setOnClickListener(this::openAddMoneyActivity);

        Button goBack = findViewById(R.id.goBack);
        goBack.setOnClickListener(v -> finish());
    }

    public void readIncomeOutcomeList() {
        List<IncomeOutcomeList> list = incomeOutcomeListDAO.getAllIncomeOutcomes();
        if (list == null || list.isEmpty()) {
            List<String> emptyMessageList = new ArrayList<>();
            emptyMessageList.add("Brak wpływów / wydatków");

            ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, emptyMessageList);

            listContainer.setAdapter(adapter);
            return;
        }

        ArrayAdapter<IncomeOutcomeList> adapter = new ArrayAdapter<IncomeOutcomeList>(this, android.R.layout.simple_list_item_1, list) {
            @Override
            public View getView(int position, View convertView, ViewGroup parent) {
                if (convertView == null) {
                    convertView = LayoutInflater.from(getContext()).inflate(android.R.layout.simple_list_item_1, parent, false);
                }

                IncomeOutcomeList item = getItem(position);
                TextView textView = convertView.findViewById(android.R.id.text1);
                textView.setText(item.toString());

                textView.setOnClickListener(v -> {
                    openEditMoneyActivity(v, item.getId());
                });

                textView.setOnLongClickListener(v -> {
                    new AlertDialog.Builder(IncomeOutcomeMainActivity.this)
                            .setTitle("Usuwanie")
                            .setMessage("Chcesz usunąć ten wpływ / wydatek?" + " (" + item.getName() + ")")
                            .setPositiveButton("Yes", (dialog, which) -> {
                                int entryId = item.getId();
                                incomeOutcomeListDAO.deleteIncomeOutcomeEntry(entryId);

                                Toast.makeText(IncomeOutcomeMainActivity.this, "Wpływ / wydatek został usunięty", Toast.LENGTH_SHORT).show();
                                readIncomeOutcomeList();
                            })
                            .setNegativeButton("No", (dialog, which) -> {
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

    public void openAddMoneyActivity(View v) {
        Intent intent = new Intent(IncomeOutcomeMainActivity.this, IncomeOutcomeEditActivity.class);
        startActivity(intent);
    }

    public void openEditMoneyActivity(View v, int entryId) {
        Intent intent = new Intent(IncomeOutcomeMainActivity.this, IncomeOutcomeEditActivity.class);
        intent.putExtra("id", entryId);
        startActivity(intent);
    }
}