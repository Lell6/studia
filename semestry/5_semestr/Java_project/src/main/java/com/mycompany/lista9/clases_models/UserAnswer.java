/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.lista9.clases_models;

import java.io.Serializable;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

/**
 *
 * @author ola
 */
public class UserAnswer implements Serializable {
    private int id;
    private String userLogin;
    private String date;
    private String answer;
    
    public UserAnswer(int id, String answer, String userLogin, String date) {
        setId(id);
        setUserLogin(userLogin);
        
        if (date == null) {        
            LocalDateTime dateTimeNow = LocalDateTime.now();
            DateTimeFormatter formatter = DateTimeFormatter.ofPattern("dd-MM-yyyy', 'HH:mm:ss");
            String formattedDateTime = dateTimeNow.format(formatter);
            setDate(formattedDateTime);
        }
        else {
            setDate(date);
        }
        
        setAnswer(answer);
    }
    
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getUserLogin() {
        return userLogin;
    }

    public void setUserLogin(String userLogin) {
        this.userLogin = userLogin;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getAnswer() {
        return answer;
    }

    public void setAnswer(String answer) {
        this.answer = answer;
    }    
}
