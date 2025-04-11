package com.example.moneytracker.table_models;

public class AlertListTable {
    public static final String TABLE_NAME = "alertList";

    public static final String COLUMN_ID = "id";
    public static final String COLUMN_NAME = "name";
    public static final String COLUMN_CATEGORY_TO_LISTEN = "categoryToListen";
    public static final String COLUMN_IS_ACTIVE = "isActive";
    public static final String COLUMN_START_DATE = "startDate";
    public static final String COLUMN_MONEY_BORDER_AMOUNT = "moneyBorderAmount";

    public static final String CREATE_TABLE =
            "CREATE TABLE " + TABLE_NAME + " (" +
                    COLUMN_ID + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
                    COLUMN_NAME + " TEXT, " +
                    COLUMN_CATEGORY_TO_LISTEN + " TEXT, " +
                    COLUMN_IS_ACTIVE + " TEXT, " +
                    COLUMN_START_DATE + " TEXT, " +
                    COLUMN_MONEY_BORDER_AMOUNT + " INTEGER);";
}
