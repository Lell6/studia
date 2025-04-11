/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.lista9.clases_models;

import java.io.Serializable;

/**
 *
 * @author ola
 */
public class ListOfAnswers implements Serializable {
    int id;
    int questionId;
    int answerId;
    
    public ListOfAnswers(int questionId, int answerId) {
        setQuestionId(questionId);
        setAnswerId(answerId);
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getQuestionId() {
        return questionId;
    }

    public void setQuestionId(int questionId) {
        this.questionId = questionId;
    }

    public int getAnswerId() {
        return answerId;
    }

    public void setAnswerId(int answerId) {
        this.answerId = answerId;
    }
}
