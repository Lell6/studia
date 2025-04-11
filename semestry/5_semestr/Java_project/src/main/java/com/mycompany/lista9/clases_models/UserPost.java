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
public class UserPost implements Serializable {
    private int id;
    private String question;
    private String date;
    private String userLogin;
    private int numberOfAnswers;
    
    public UserPost(int id, String question, String userLogin, int numOfAnswers, String date) {
        setId(id);
        setQuestion(question);
        
        if (date == null) {        
            LocalDateTime dateTimeNow = LocalDateTime.now();
            DateTimeFormatter formatter = DateTimeFormatter.ofPattern("dd-MM-yyyy', 'HH:mm:ss");
            String formattedDateTime = dateTimeNow.format(formatter);
            setDate(formattedDateTime);
        }
        else {
            setDate(date);
        }
        
        setNumberOfAnswers(numOfAnswers);
        setUserLogin(userLogin);
    }
    
    public int getId() {
        return id;
    }
    
    public String getQuestion() {
        return question;
    }
    
    public String getDate() {
        return date;
    }
    
    public int getNumberOfAnswers() {
        return numberOfAnswers;
    }
    
    public void setId(int id) {
        this.id = id;
    }
    
    public void setQuestion(String question) {
        this.question = question;
    }
    
    public void setDate(String date) {
        this.date = date;
    } 
    
    public void setNumberOfAnswers(int numberOfAnswers) {
        this.numberOfAnswers = numberOfAnswers;
    }

    public String getUserLogin() {
        return userLogin;
    }

    public void setUserLogin(String userLogin) {
        this.userLogin = userLogin;
    }
}
