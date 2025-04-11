package com.example.moneytracker.DAOs;

import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;

import com.example.moneytracker.pre_models.BudgetHistory;
import com.example.moneytracker.table_models.BudgetHistoryTable;

import java.util.ArrayList;
import java.util.List;

public class BudgetHistoryDAO {
    private SQLiteDatabase db;

    public BudgetHistoryDAO(SQLiteDatabase db) {
        this.db = db;
    }

    private BudgetHistory getFromCurrentCursorPoint(Cursor cursor) {
        int id = cursor.getInt(cursor.getColumnIndex(BudgetHistoryTable.COLUMN_ID));
        int transactionId = cursor.getInt(cursor.getColumnIndex(BudgetHistoryTable.COLUMN_TRANSACTION_ID));
        int transactionPrice = cursor.getInt(cursor.getColumnIndex(BudgetHistoryTable.COLUMN_TRANSACTION_PRICE));
        String transactionName = cursor.getString(cursor.getColumnIndex(BudgetHistoryTable.COLUMN_TRANSACTION_NAME));
        String transactionType = cursor.getString(cursor.getColumnIndex(BudgetHistoryTable.COLUMN_TRANSACTION_TYPE));

        return new BudgetHistory(id, transactionId, transactionPrice, transactionName, transactionType);
    }

    private ContentValues createFilledContentValues(ContentValues values, BudgetHistory budgetHistoryEntry) {
        values.put(BudgetHistoryTable.COLUMN_TRANSACTION_ID, budgetHistoryEntry.getTransactionId());
        values.put(BudgetHistoryTable.COLUMN_TRANSACTION_PRICE, budgetHistoryEntry.getTransactionPrice());
        values.put(BudgetHistoryTable.COLUMN_TRANSACTION_NAME, budgetHistoryEntry.getTransactionName());
        values.put(BudgetHistoryTable.COLUMN_TRANSACTION_TYPE, budgetHistoryEntry.getTransactionType());

        return values;
    }

    public long addEntryToBudgetHistory(BudgetHistory budgetHistoryEntry) {
        ContentValues values = createFilledContentValues(new ContentValues(), budgetHistoryEntry);
        return db.insert(BudgetHistoryTable.TABLE_NAME, null, values);
    }

    public List<BudgetHistory> getAllBudgetHistory() {
        List<BudgetHistory> history = new ArrayList<>();
        String selectQuery = "SELECT  * FROM " + BudgetHistoryTable.TABLE_NAME;

        Cursor cursor = db.rawQuery(selectQuery, null);
        if (cursor != null && cursor.moveToFirst()) {
            do {
                history.add(getFromCurrentCursorPoint(cursor));
            } while (cursor.moveToNext());
            cursor.close();
            return history;
        }

        if (cursor != null) {
            cursor.close();
        }

        return history;
    }

    public BudgetHistory getBudgetHistoryEntry(int entryId) {
        String selectQuery = "SELECT  * FROM " + BudgetHistoryTable.TABLE_NAME + " WHERE "
                + BudgetHistoryTable.COLUMN_ID + " = " + entryId;

        Cursor cursor = db.rawQuery(selectQuery, null);
        if (cursor != null && cursor.moveToFirst()) {
            BudgetHistory entry = getFromCurrentCursorPoint(cursor);
            cursor.close();
            return entry;
        }

        if (cursor != null) {
            cursor.close();
        }

        return null;
    }

    public long updateBudgetHistoryEntry(int entryId, BudgetHistory entry) {
        ContentValues values = createFilledContentValues(new ContentValues(), entry);
        return db.update(BudgetHistoryTable.TABLE_NAME, values, "id = ?", new String[]{String.valueOf(entryId)});
    }

    public long deleteBudgetHistoryEntry(int entryId) {
        return db.delete(BudgetHistoryTable.TABLE_NAME, "id = ?", new String[]{String.valueOf(entryId)});
    }
}
