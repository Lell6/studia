package com.example.moneytracker;

import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import com.example.moneytracker.table_models.AlertListTable;
import com.example.moneytracker.table_models.BudgetHistoryTable;
import com.example.moneytracker.table_models.IncomeOutcomeCategoryTable;
import com.example.moneytracker.table_models.IncomeOutcomeListTable;


public class DatabaseHelper extends SQLiteOpenHelper {
    private static final String DATABASE_NAME = "budget.db";
    private static final int DATABASE_VERSION = 11;

    public DatabaseHelper(Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        String query = "SELECT name FROM sqlite_master WHERE type='table' AND name='" + IncomeOutcomeListTable.TABLE_NAME + "';";
        Cursor cursor = db.rawQuery(query, null);

        if (!cursor.moveToFirst()) {
            db.execSQL(IncomeOutcomeListTable.CREATE_TABLE);
        }

        query = "SELECT name FROM sqlite_master WHERE type='table' AND name='" + IncomeOutcomeCategoryTable.TABLE_NAME + "';";
        cursor = db.rawQuery(query, null);
        if (!cursor.moveToFirst()) {
            db.execSQL(IncomeOutcomeCategoryTable.CREATE_TABLE);
        }

        query = "SELECT name FROM sqlite_master WHERE type='table' AND name='" + BudgetHistoryTable.TABLE_NAME + "';";
        cursor = db.rawQuery(query, null);
        if (!cursor.moveToFirst()) {
            db.execSQL(BudgetHistoryTable.CREATE_TABLE);
        }

        query = "SELECT name FROM sqlite_master WHERE type='table' AND name='" + AlertListTable.TABLE_NAME + "';";
        cursor = db.rawQuery(query, null);
        if (!cursor.moveToFirst()) {
            db.execSQL(AlertListTable.CREATE_TABLE);
        }

        cursor.close();
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS " + BudgetHistoryTable.TABLE_NAME);
        db.execSQL("DROP TABLE IF EXISTS " + AlertListTable.TABLE_NAME);
        db.execSQL("DROP TABLE IF EXISTS " + IncomeOutcomeListTable.TABLE_NAME);
        db.execSQL("DROP TABLE IF EXISTS " + IncomeOutcomeCategoryTable.TABLE_NAME);

        onCreate(db);
    }

    public SQLiteDatabase getWritableDb() {
        return getWritableDatabase();
    }

    public SQLiteDatabase getReadableDb() {
        return getReadableDatabase();
    }
}
