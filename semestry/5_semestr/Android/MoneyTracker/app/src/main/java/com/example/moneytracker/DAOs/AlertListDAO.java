package com.example.moneytracker.DAOs;

import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;

import com.example.moneytracker.pre_models.IncomeOutcomeList;
import com.example.moneytracker.table_models.AlertListTable;
import com.example.moneytracker.pre_models.AlertList;

import java.util.ArrayList;
import java.util.List;

public class AlertListDAO {
    private SQLiteDatabase db;

    public AlertListDAO(SQLiteDatabase db) {
        this.db = db;
    }

    private AlertList getFromCurrentCursorPoint(Cursor cursor) {
        int id = cursor.getInt(cursor.getColumnIndex(AlertListTable.COLUMN_ID));
        String name = cursor.getString(cursor.getColumnIndex(AlertListTable.COLUMN_NAME));
        String categoryToListen = cursor.getString(cursor.getColumnIndex(AlertListTable.COLUMN_CATEGORY_TO_LISTEN));
        String isActive = cursor.getString(cursor.getColumnIndex(AlertListTable.COLUMN_IS_ACTIVE));
        String endDate = cursor.getString(cursor.getColumnIndex(AlertListTable.COLUMN_START_DATE));
        int moneyBorderAmount = cursor.getInt(cursor.getColumnIndex(AlertListTable.COLUMN_MONEY_BORDER_AMOUNT));

        return new AlertList(id, name, categoryToListen, isActive, endDate, moneyBorderAmount);
    }

    private ContentValues createFilledContentValues(ContentValues values, AlertList alert) {
        values.put(AlertListTable.COLUMN_NAME, alert.getName());
        values.put(AlertListTable.COLUMN_CATEGORY_TO_LISTEN, alert.getCategoryToListen());
        values.put(AlertListTable.COLUMN_IS_ACTIVE, alert.getIsActive());
        values.put(AlertListTable.COLUMN_START_DATE, alert.getStartDate());
        values.put(AlertListTable.COLUMN_MONEY_BORDER_AMOUNT, alert.getMoneyBorderAmount());

        return values;
    }

    public long addEntryToAlertList(AlertList alert) {
        ContentValues values = createFilledContentValues(new ContentValues(), alert);
        return db.insert(AlertListTable.TABLE_NAME, null, values);
    }

    public List<AlertList> getAllAlertLists() {
        List<AlertList> alerts = new ArrayList<>();
        String selectQuery = "SELECT * FROM " + AlertListTable.TABLE_NAME;

        Cursor cursor = db.rawQuery(selectQuery, null);
        if (cursor != null && cursor.moveToFirst()) {
            do {
                alerts.add(getFromCurrentCursorPoint(cursor));
            } while (cursor.moveToNext());
            cursor.close();
            return alerts;
        }

        if (cursor != null) {
            cursor.close();
        }

        return alerts;
    }

    public AlertList getAlertListEntry(int entryId) {
        String selectQuery = "SELECT * FROM " + AlertListTable.TABLE_NAME + " WHERE "
                + AlertListTable.COLUMN_ID + " = " + entryId;
        Cursor cursor = db.rawQuery(selectQuery, null);
        if (cursor != null && cursor.moveToFirst()) {
            AlertList entry = getFromCurrentCursorPoint(cursor);
            cursor.close();
            return entry;
        }

        if (cursor != null) {
            cursor.close();
        }
        return null;
    }

    public long updateAlertListEntry(int entryId, AlertList alert) {
        ContentValues values = createFilledContentValues(new ContentValues(), alert);
        return db.update(AlertListTable.TABLE_NAME, values, "id = ?", new String[]{String.valueOf(entryId)});
    }

    public long deleteAlertListEntry(int entryId) {
        return db.delete(AlertListTable.TABLE_NAME, "id = ?", new String[]{String.valueOf(entryId)});
    }
}
