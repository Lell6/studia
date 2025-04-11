package com.example.moneytracker.pre_models;

public class BudgetHistory {
    private int id;
    //private int currentBudget;
    private int transactionId;
    private int transactionPrice;
    private String transactionName;
    private String transactionType;

    public BudgetHistory(int id, int transactionId, double transactionPrice, String transactionName, String transactionType) {
        this.id = id;
        this.transactionId = transactionId;
        this.transactionPrice = (int) transactionPrice * 100;
        this.transactionName = transactionName;
        this.transactionType = transactionType;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getTransactionId() {
        return transactionId;
    }

    public void setTransactionId(int transactionId) {
        this.transactionId = transactionId;
    }

    public double getTransactionPrice() {
        return Double.parseDouble(String.format("%.2f", (double) transactionPrice / 100));
    }

    public void setTransactionPrice(double transactionPrice) {
        this.transactionPrice = (int) transactionPrice * 100;
    }

    public String getTransactionName() {
        return transactionName;
    }

    public void setTransactionName(String transactionName) {
        this.transactionName = transactionName;
    }

    public String getTransactionType() {
        return transactionType;
    }

    public void setTransactionType(String transactionType) {
        this.transactionType = transactionType;
    }

    @Override
    public String toString() {
        return  "Kwota transakcji: " + String.format("%.2f", (double) transactionPrice / 100) +
                "\nNazwa transakcji: " + transactionName +
                "\nTyp transakcji: " + transactionType;
    }
}
