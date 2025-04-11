package com.example.moneytracker.pre_models;

public class AlertList {
    private int id;
    private String name;
    private String categoryToListen;
    private String startDate;
    private int moneyBorderAmount;
    private String isActive;

    public AlertList(int id, String name, String categoryToListen, String isActive, String startDate, double moneyBorderAmount) {
        this.id = id;
        this.name = name;
        this.categoryToListen = categoryToListen;
        this.startDate = startDate;
        this.moneyBorderAmount = (int) moneyBorderAmount * 100;
        this.isActive = isActive;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getCategoryToListen() {
        return categoryToListen;
    }

    public void setCategoryToListen(String categoryToListen) {
        this.categoryToListen = categoryToListen;
    }

    public String getStartDate() {
        return startDate;
    }

    public void setStartDate(String endDate) {
        this.startDate = endDate;
    }

    public double getMoneyBorderAmount() {
        return Double.parseDouble(String.format("%.2f", (double) moneyBorderAmount / 100));
    }

    public void setMoneyBorderAmount(double moneyBorderAmount) {
        this.moneyBorderAmount = (int) moneyBorderAmount * 100;
    }

    public String getIsActive() {
        return isActive;
    }

    public void setIsActive(String isActive) {
        this.isActive = isActive;
    }

    @Override
    public String toString() {
        return  "Nazwa: " + name +
                " Kategoria nasłuchiwana: " + categoryToListen +
                " Od kiedy nasłuchiwany: " + startDate +
                " Kwota graniczna: " + String.format("%.2f", (double) moneyBorderAmount / 100) + "zł. ";
    }
}