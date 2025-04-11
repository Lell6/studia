/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.lista9.answer;

import com.mycompany.lista9.clases_models.ListOfAnswers;
import java.io.*;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.*;
import javax.servlet.ServletContext;

public class ListOfAnswersRepository {    
    private String url = "jdbc:mysql://localhost:3306/stack_overflow";
    private String usernameDb = "root";
    private String passwordDb = "";
    
    private String queryInsert = "INSERT INTO listofanswers (postId, answerId) VALUES (?,?)";
    
    public List<ListOfAnswers> getAnswersListFromFile(ServletContext servletContext) {
        List<ListOfAnswers> answersLists = new ArrayList<>();
        String filePath = servletContext.getRealPath("/WEB-INF/postAnswerList.ser");
        
        try (ObjectInputStream in = new ObjectInputStream(new FileInputStream(filePath))) {
            while (true) {
                try {
                    ListOfAnswers answer = (ListOfAnswers) in.readObject();
                    answersLists.add(answer);
                } catch (EOFException e) {
                    break;
                } catch (ClassNotFoundException e) {
                    e.printStackTrace();
                }
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
        
        return new ArrayList(answersLists);
    }
    
    public List<ListOfAnswers> getAnswersListFromFileForQuestion(ServletContext servletContext, int id) {
        List<ListOfAnswers> answers = getAnswersListFromFile(servletContext);
        List<ListOfAnswers> filteredAnswers = new ArrayList<>();
        
        for (ListOfAnswers entry : answers) {
            if (entry.getQuestionId() == id) {
                filteredAnswers.add(entry);
            }
        }
        
        return filteredAnswers;
    }
    
    public int getLastId(ServletContext servletContext) {
        List<ListOfAnswers> answers = getAnswersListFromFile(servletContext);
        int maxId = 0;
        
        for (ListOfAnswers answer : answers) {
            if (answer.getId() > maxId) {
                maxId = answer.getId();
            }
        }
        
        return maxId + 1;
    }
    
    public void saveAnswerToPost(int postId, int answerId) {
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                PreparedStatement stmt = conn.prepareStatement(queryInsert)) {
                stmt.setInt(1, postId);
                stmt.setInt(2, answerId);

                int count = stmt.executeUpdate();
            }
            catch (SQLException e) {
                System.out.println("SQL Error: " + e.getMessage());
                return;
            }
        } catch (ClassNotFoundException e) {
            System.out.println("MySQL JDBC Driver not found: ");
            return;
        }
    }
}
