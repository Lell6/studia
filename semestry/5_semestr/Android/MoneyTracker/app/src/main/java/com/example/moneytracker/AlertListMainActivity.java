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

import com.example.moneytracker.DAOs.AlertListDAO;
import com.example.moneytracker.DAOs.AlertListDAO;
import com.example.moneytracker.pre_models.AlertList;

import java.util.ArrayList;
import java.util.List;

public class AlertListMainActivity extends AppCompatActivity {
    DatabaseHelper databaseHelper;
    AlertListDAO alertListDAO;
    ListView listContainer;

    @Override
    public void onResume() {
        super.onResume();
        readAlertList();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_alert_list_main);

        databaseHelper = new DatabaseHelper(this);
        alertListDAO = new AlertListDAO(databaseHelper.getWritableDb());
        listContainer = findViewById(R.id.listView);

        readAlertList();

        Button addNewAlert = findViewById(R.id.addNew);
        addNewAlert.setOnClickListener(v -> openAddAlertListActivity());

        Button goBack = findViewById(R.id.goBack);
        goBack.setOnClickListener(v -> finish());
    }

    public void readAlertList() {
        List<AlertList> list = alertListDAO.getAllAlertLists();
        if (list == null || list.isEmpty()) {
            List<String> emptyMessageList = new ArrayList<>();
            emptyMessageList.add("Brak alertów");

            ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, emptyMessageList);

            listContainer.setAdapter(adapter);
            return;
        }

        ArrayAdapter<AlertList> adapter = new ArrayAdapter<AlertList>(this, android.R.layout.simple_list_item_1, list) {
            @Override
            public View getView(int position, View convertView, ViewGroup parent) {
                if (convertView == null) {
                    convertView = LayoutInflater.from(getContext()).inflate(android.R.layout.simple_list_item_1, parent, false);
                }

                AlertList item = getItem(position);
                TextView textView = convertView.findViewById(android.R.id.text1);
                textView.setText(item.toString());

                textView.setOnClickListener(v -> openEditAlertListActivity(v, item.getId()));

                textView.setOnLongClickListener(v -> {
                    new AlertDialog.Builder(AlertListMainActivity.this)
                            .setTitle("Usuwanie")
                            .setMessage("Chcesz usunąć ten alert?" + " (" + item.getName() + ")")
                            .setPositiveButton("Tak", (dialog, which) -> {
                                int categoryId = item.getId();
                                alertListDAO.deleteAlertListEntry(categoryId);

                                Toast.makeText(AlertListMainActivity.this, "Alert został usunięty", Toast.LENGTH_SHORT).show();
                                readAlertList();
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

    public void openAddAlertListActivity() {
        Intent intent = new Intent(AlertListMainActivity.this, AlertListEditActivity.class);
        startActivity(intent);
    }

    public void openEditAlertListActivity(View v, int categoryId) {
        Intent intent = new Intent(AlertListMainActivity.this, AlertListEditActivity.class);
        intent.putExtra("id", categoryId);
        startActivity(intent);
    }
}