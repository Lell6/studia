package com.example.moneytracker.DAOs;

import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;

import com.example.moneytracker.table_models.IncomeOutcomeCategoryTable;
import com.example.moneytracker.pre_models.IncomeOutcomeCategory;

import java.util.ArrayList;
import java.util.List;

public class IncomeOutcomeCategoryDAO {
    private SQLiteDatabase db;

    public IncomeOutcomeCategoryDAO(SQLiteDatabase db) {
        this.db = db;
    }

    private IncomeOutcomeCategory getFromCurrentCursorPoint(Cursor cursor) {
        int id = cursor.getInt(cursor.getColumnIndex(IncomeOutcomeCategoryTable.COLUMN_ID));
        String name = cursor.getString(cursor.getColumnIndex(IncomeOutcomeCategoryTable.COLUMN_NAME));
        String type = cursor.getString(cursor.getColumnIndex(IncomeOutcomeCategoryTable.COLUMN_TYPE));

        return new IncomeOutcomeCategory(id, name, type);
    }

    private ContentValues createFilledContentValues(ContentValues values, IncomeOutcomeCategory category) {
        values.put(IncomeOutcomeCategoryTable.COLUMN_NAME, category.getName());
        values.put(IncomeOutcomeCategoryTable.COLUMN_TYPE, category.getType());
        return values;
    }

    public long addEntryToIncomeOutcomeCategoryList(IncomeOutcomeCategory category) {
        ContentValues values = createFilledContentValues(new ContentValues(), category);
        return db.insert(IncomeOutcomeCategoryTable.TABLE_NAME, null, values);
    }

    public List<IncomeOutcomeCategory> getAllIncomeOutcomeCategories() {
        List<IncomeOutcomeCategory> categories = new ArrayList<>();
        String selectQuery = "SELECT * FROM " + IncomeOutcomeCategoryTable.TABLE_NAME;

        Cursor cursor = db.rawQuery(selectQuery, null);

        if (cursor != null && cursor.moveToFirst()) {
            do {
                categories.add(getFromCurrentCursorPoint(cursor));
            } while (cursor.moveToNext());
            cursor.close();
            return categories;
        }

        if (cursor != null) {
            cursor.close();
        }

        return null;
    }

    public IncomeOutcomeCategory getIncomeOutcomeCategoryEntry(int entryId) {
        String selectQuery = "SELECT * FROM " + IncomeOutcomeCategoryTable.TABLE_NAME + " WHERE "
                + IncomeOutcomeCategoryTable.COLUMN_ID + " = ?";
        Cursor cursor = db.rawQuery(selectQuery, new String[]{String.valueOf(entryId)});

        if (cursor != null && cursor.moveToFirst()) {
            IncomeOutcomeCategory entry = getFromCurrentCursorPoint(cursor);
            cursor.close();
            return entry;
        }

        if (cursor != null) {
            cursor.close();
        }
        return null;
    }

    public IncomeOutcomeCategory getIncomeOutcomeCategoryEntryByName(String name) {
        String selectQuery = "SELECT * FROM " + IncomeOutcomeCategoryTable.TABLE_NAME + " WHERE "
                + IncomeOutcomeCategoryTable.COLUMN_NAME + " = ?";
        Cursor cursor = db.rawQuery(selectQuery,  new String[]{name});

        if (cursor != null && cursor.moveToFirst()) {
            IncomeOutcomeCategory entry = getFromCurrentCursorPoint(cursor);
            cursor.close();
            return entry;
        }

        if (cursor != null) {
            cursor.close();
        }

        return null;
    }

    public long updateIncomeOutcomeCategoryEntry(int entryId, IncomeOutcomeCategory category) {
        ContentValues values = createFilledContentValues(new ContentValues(), category);
        return db.update(IncomeOutcomeCategoryTable.TABLE_NAME, values, "id = ?", new String[]{String.valueOf(entryId)});
    }

    public long deleteIncomeOutcomeCategoryEntry(int entryId) {
        return db.delete(IncomeOutcomeCategoryTable.TABLE_NAME, "id = ?", new String[]{String.valueOf(entryId)});
    }
}
