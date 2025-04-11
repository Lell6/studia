package com.example.moneytracker.table_models;

public class IncomeOutcomeListTable {
    public static final String TABLE_NAME = "incomeOutcomeList";

    public static final String COLUMN_ID = "id";
    public static final String COLUMN_NAME = "name";
    public static final String COLUMN_TYPE = "type";
    public static final String COLUMN_REPEAT = "repeat";
    public static final String COLUMN_CATEGORY = "category";
    public static final String COLUMN_STATUS = "status";
    public static final String COLUMN_DATE = "date";
    public static final String COLUMN_MONEY_AMOUNT = "moneyAmount";

    public static final String CREATE_TABLE =
            "CREATE TABLE " + TABLE_NAME + " (" +
                    COLUMN_ID + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
                    COLUMN_NAME + " TEXT, " +
                    COLUMN_TYPE + " TEXT, " +
                    COLUMN_REPEAT + " TEXT, " +
                    COLUMN_CATEGORY + " TEXT, " +
                    COLUMN_STATUS + " TEXT, " +
                    COLUMN_DATE + " TEXT, " +
                    COLUMN_MONEY_AMOUNT  + " TEXT);";
}
