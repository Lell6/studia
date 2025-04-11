package com.example.moneytracker.pre_models;

public class IncomeOutcomeList {
    private int id;
    private String name;
    private String type;
    private String repeat;
    private String category;
    private String status;
    private String date;
    private int moneyAmount;

    public IncomeOutcomeList() {

    }

    public IncomeOutcomeList(int id, String name, String type, String repeat, String category,
                             String status, String date, double moneyAmount) {
        this.id = id;
        this.name = name; //np Wygrana w loto, Opłata internet
        this.type = type; // wpływ / wydatek
        this.repeat = repeat; // stały / jednorazowy
        this.category = category; // np czynsz, produkty, praca
        this.status = status; // aktywna nieaktywna
        this.date = date; // od dzisiaj
        this.moneyAmount = (int) moneyAmount * 100; // np 2000zł
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

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getRepeat() {
        return repeat;
    }

    public void setRepeat(String repeat) {
        this.repeat = repeat;
    }

    public String getCategory() {
        return category;
    }

    public void setCategory(String category) {
        this.category = category;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public double getMoneyAmount() {
        return Double.parseDouble(String.format("%.2f", (double) moneyAmount / 100));
    }

    public void setMoneyAmount(double moneyAmount) {
        this.moneyAmount = (int) moneyAmount * 100;
    }

    @Override
    public String toString() {
        return  "Nazwa: " + name +
                " Typ: " + type +
                " Powtarzalność: " + repeat +
                " Kategoria: " + category +
                " Status: " + status + "\n" +
                "Od kiedy: " + date +
                " Kwota: " + String.format("%.2f", (double) moneyAmount / 100) + "zł. ";
    }
}