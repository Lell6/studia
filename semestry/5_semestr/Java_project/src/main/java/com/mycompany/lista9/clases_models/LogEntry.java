/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.lista9.clases_models;
import java.time.ZonedDateTime;

/**
 *
 * @author ola
 */
public class LogEntry {
    private int id;
    private String date;
    private String message;
    private String userIp;

    public LogEntry(String message, String ip) {
        ZonedDateTime dateTimeNow = ZonedDateTime.now();
        setDate(dateTimeNow.toString());
        
        setMessage(message);
        setUserIp(ip);
    }
    
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public String getUserIp() {
        return userIp;
    }

    public void setUserIp(String userIp) {
        this.userIp = userIp;
    }
    
    public String toString() {
        return "User " + getUserIp() + " at " + getDate() + " failed to login with message: " + getMessage();
    }
}
