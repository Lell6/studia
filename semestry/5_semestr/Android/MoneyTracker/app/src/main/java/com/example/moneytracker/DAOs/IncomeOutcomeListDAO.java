package com.example.moneytracker.DAOs;

import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;

import com.example.moneytracker.pre_models.IncomeOutcomeCategory;
import com.example.moneytracker.table_models.IncomeOutcomeListTable;
import com.example.moneytracker.pre_models.IncomeOutcomeList;

import java.util.ArrayList;
import java.util.List;

public class IncomeOutcomeListDAO {
    private SQLiteDatabase db;

    public IncomeOutcomeListDAO(SQLiteDatabase db) {
        this.db = db;
    }

    private IncomeOutcomeList getFromCurrentCursorPoint(Cursor cursor) {
        int id = cursor.getInt(cursor.getColumnIndex(IncomeOutcomeListTable.COLUMN_ID));
        String name = cursor.getString(cursor.getColumnIndex(IncomeOutcomeListTable.COLUMN_NAME));
        String type = cursor.getString(cursor.getColumnIndex(IncomeOutcomeListTable.COLUMN_TYPE));
        String repeat = cursor.getString(cursor.getColumnIndex(IncomeOutcomeListTable.COLUMN_REPEAT));
        String category = cursor.getString(cursor.getColumnIndex(IncomeOutcomeListTable.COLUMN_CATEGORY));
        String status = cursor.getString(cursor.getColumnIndex(IncomeOutcomeListTable.COLUMN_STATUS));
        String date = cursor.getString(cursor.getColumnIndex(IncomeOutcomeListTable.COLUMN_DATE));
        int moneyAmount = cursor.getInt(cursor.getColumnIndex(IncomeOutcomeListTable.COLUMN_MONEY_AMOUNT));

        return new IncomeOutcomeList(id, name, type, repeat, category, status, date, moneyAmount);
    }

    private ContentValues createFilledContentValues(ContentValues values, IncomeOutcomeList incomeOutcomeList) {
        values.put(IncomeOutcomeListTable.COLUMN_NAME, incomeOutcomeList.getName());
        values.put(IncomeOutcomeListTable.COLUMN_TYPE, incomeOutcomeList.getType());
        values.put(IncomeOutcomeListTable.COLUMN_REPEAT, incomeOutcomeList.getRepeat());
        values.put(IncomeOutcomeListTable.COLUMN_CATEGORY, incomeOutcomeList.getCategory());
        values.put(IncomeOutcomeListTable.COLUMN_STATUS, incomeOutcomeList.getStatus());
        values.put(IncomeOutcomeListTable.COLUMN_DATE, incomeOutcomeList.getDate());
        values.put(IncomeOutcomeListTable.COLUMN_MONEY_AMOUNT, incomeOutcomeList.getMoneyAmount());

        return values;
    }

    public long addEntryToIncomeOutcomeList(IncomeOutcomeList incomeOutcomeList) {
        ContentValues values = createFilledContentValues(new ContentValues(), incomeOutcomeList);
        return db.insert(IncomeOutcomeListTable.TABLE_NAME, null, values);
    }

    public List<IncomeOutcomeList> getAllIncomeOutcomes() {
        List<IncomeOutcomeList> incomeOutcomeLists = new ArrayList<>();
        String selectQuery = "SELECT * FROM " + IncomeOutcomeListTable.TABLE_NAME;

        Cursor cursor = db.rawQuery(selectQuery, null);
        if (cursor != null && cursor.moveToFirst()) {
            do {
                incomeOutcomeLists.add(getFromCurrentCursorPoint(cursor));
            } while (cursor.moveToNext());
            cursor.close();
            return incomeOutcomeLists;
        }

        if (cursor != null) {
            cursor.close();
        }

        return incomeOutcomeLists;
    }

    public List<IncomeOutcomeList> getAllOutcomes() {
        List<IncomeOutcomeList> incomeOutcomeLists = new ArrayList<>();
        String selectQuery = "SELECT * FROM " + IncomeOutcomeListTable.TABLE_NAME + " WHERE " +
                IncomeOutcomeListTable.COLUMN_CATEGORY  + " = 'Wydatek'";

        Cursor cursor = db.rawQuery(selectQuery, null);
        if (cursor != null && cursor.moveToFirst()) {
            do {
                incomeOutcomeLists.add(getFromCurrentCursorPoint(cursor));
            } while (cursor.moveToNext());
            cursor.close();
            return incomeOutcomeLists;
        }

        if (cursor != null) {
            cursor.close();
        }

        return incomeOutcomeLists;
    }

    public List<IncomeOutcomeList> getAllIncomes() {
        List<IncomeOutcomeList> incomeOutcomeLists = new ArrayList<>();
        String selectQuery = "SELECT * FROM " + IncomeOutcomeListTable.TABLE_NAME + " WHERE " +
                IncomeOutcomeListTable.COLUMN_CATEGORY  + " = 'Wpływ'";

        Cursor cursor = db.rawQuery(selectQuery, null);
        if (cursor != null && cursor.moveToFirst()) {
            do {
                incomeOutcomeLists.add(getFromCurrentCursorPoint(cursor));
            } while (cursor.moveToNext());
            cursor.close();
            return incomeOutcomeLists;
        }

        if (cursor != null) {
            cursor.close();
        }

        return incomeOutcomeLists;
    }

    public IncomeOutcomeList getIncomeOutcomeEntry(int entryId) {
        String selectQuery = "SELECT * FROM " + IncomeOutcomeListTable.TABLE_NAME + " WHERE "
                + IncomeOutcomeListTable.COLUMN_ID + " = " + entryId;
        Cursor cursor = db.rawQuery(selectQuery, null);
        if (cursor != null && cursor.moveToFirst()) {
            IncomeOutcomeList entry = getFromCurrentCursorPoint(cursor);
            cursor.close();
            return entry;
        }

        if (cursor != null) {
            cursor.close();
        }

        return null;
    }

    public double getIncomeOutcomeEntryMoneyAccumulatedByCategoryName(String category) {
        double moneyAccumulated = 0.00;

        String selectQuery = "SELECT * FROM " + IncomeOutcomeListTable.TABLE_NAME + " WHERE "
                + IncomeOutcomeListTable.COLUMN_CATEGORY + " = '" + category + "'";
        Cursor cursor = db.rawQuery(selectQuery, null);

        if (cursor != null && cursor.moveToFirst()) {
            do {
                IncomeOutcomeList entry = getFromCurrentCursorPoint(cursor);
                moneyAccumulated += entry.getMoneyAmount();
            } while (cursor.moveToNext());
            cursor.close();
        }

        if (cursor != null) {
            cursor.close();
        }

        return moneyAccumulated;
    }

    public long updateIncomeOutcomeEntry(int entryId, IncomeOutcomeList entry) {
        ContentValues values = createFilledContentValues(new ContentValues(), entry);
        return db.update(IncomeOutcomeListTable.TABLE_NAME, values, "id = ?", new String[]{String.valueOf(entryId)});
    }

    public long deleteIncomeOutcomeEntry(int entryId) {
        return db.delete(IncomeOutcomeListTable.TABLE_NAME, "id = ?", new String[]{String.valueOf(entryId)});
    }
}