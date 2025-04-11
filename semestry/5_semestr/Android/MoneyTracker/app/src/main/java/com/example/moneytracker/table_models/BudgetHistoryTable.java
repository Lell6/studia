package com.example.moneytracker.table_models;

public class BudgetHistoryTable {
    public static final String TABLE_NAME = "budgetHistory";

    public static final String COLUMN_ID = "id";
    public static final String COLUMN_TRANSACTION_ID = "transactionId";
    public static final String COLUMN_TRANSACTION_PRICE = "transactionPrice";
    public static final String COLUMN_TRANSACTION_NAME = "transactionName";
    public static final String COLUMN_TRANSACTION_TYPE = "transactionType";

    public static final String CREATE_TABLE =
            "CREATE TABLE " + TABLE_NAME + " (" +
                    COLUMN_ID + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
                    COLUMN_TRANSACTION_ID + " INTEGER, " +
                    COLUMN_TRANSACTION_PRICE + " INTEGER, " +
                    COLUMN_TRANSACTION_NAME + " TEXT, " +
                    COLUMN_TRANSACTION_TYPE + " TEXT);";
}
