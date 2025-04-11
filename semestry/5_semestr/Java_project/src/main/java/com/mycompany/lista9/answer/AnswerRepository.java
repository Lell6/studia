/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.lista9.answer;

import com.mycompany.lista9.clases_models.UserAnswer;
import com.mycompany.lista9.clases_models.UserPost;
import java.io.*;
import java.util.*;
import javax.servlet.ServletContext;
import java.sql.*;

/**
 *
 * @author ola
 */
public class AnswerRepository {
    private String queryInsert = "INSERT INTO answers (answer, userLogin, date) VALUES (?,?,?)";
    
    private String queryUpdateAnswer = "UPDATE answers SET answer = ? WHERE Id = ?";
    
    private String queryDelete = "DELETE answers WHERE Id = ?";
    
    private String querySelectAll = "SELECT * FROM answers";
    private String querySelectPost = "SELECT " +
                                     "    posts.Id AS postId, \n" + 
                                     "    answers.Id AS answerId, \n" +
                                     "    answers.answer, \n" +
                                     "    answers.userLogin AS answerUserLogin, \n" +
                                     "    answers.date AS answerDate\n" +
                                     "FROM \n" +
                                     "    listofanswers\n" +
                                     "INNER JOIN \n" +
                                     "    answers ON listofanswers.answerId = answers.Id\n" +
                                     "INNER JOIN \n" +
                                     "    posts ON listofanswers.postId = posts.Id\n" + 
                                     "WHERE posts.Id = ?";
    
    private String querySelectId = "SELECT * FROM answers WHERE Id = ?";
    private String querySelectLogin = "SELECT * FROM answers WHERE userLogin = ?";
    
    private String url = "jdbc:mysql://localhost:3306/stack_overflow";
    private String usernameDb = "root";
    private String passwordDb = "";
    
    public List<UserAnswer> getAnswersAll() {
        List<UserAnswer> answers = new ArrayList<>();
        
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                PreparedStatement stmt = conn.prepareStatement(querySelectAll)) {

                try (ResultSet rs = stmt.executeQuery()) {
                    while (rs.next()) {
                        UserAnswer answer = new UserAnswer(
                                    rs.getInt("Id"),
                                    rs.getString("answer"),
                                    rs.getString("userLogin"),
                                    rs.getString("date"));
                        answers.add(answer);
                    }
                }
            }
            catch (SQLException e) {
                System.out.println("answerALL: SQL Error: " + e.getMessage());
            }
        } catch (ClassNotFoundException e) {
            System.out.println("MySQL JDBC Driver not found: ");
        }
        
        return answers;
    }
    
    public List<UserAnswer> getAnswerByPost(int postId) {
        List<UserAnswer> answers = new ArrayList<>();
        
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                PreparedStatement stmt = conn.prepareStatement(querySelectPost)) {
                stmt.setInt(1, postId);

                try (ResultSet rs = stmt.executeQuery()) {
                    while (rs.next()) {
                        UserAnswer answer = new UserAnswer(
                                    rs.getInt("answerId"),
                                    rs.getString("answer"),
                                    rs.getString("answerUserLogin"),
                                    rs.getString("answerDate"));
                        answers.add(answer);
                    }
                }
            }
            catch (SQLException e) {
                System.out.println("answerPOST: SQL Error: " + e.getMessage());
            }
        } catch (ClassNotFoundException e) {
            System.out.println("MySQL JDBC Driver not found: ");
        }
        
        return answers;
    }
    
    public UserAnswer getAnswerById(int id) {
        UserAnswer answer = null;
        
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                PreparedStatement stmt = conn.prepareStatement(querySelectId)) {
                stmt.setInt(1, id);

                try (ResultSet rs = stmt.executeQuery()) {
                    if (rs.next()) {
                        answer = new UserAnswer(
                                    rs.getInt("Id"),
                                    rs.getString("answer"),
                                    rs.getString("userLogin"),
                                    rs.getString("date"));
                    }
                }
            }
            catch (SQLException e) {
                System.out.println("answerID: SQL Error: " + e.getMessage());
            }
        } catch (ClassNotFoundException e) {
            System.out.println("MySQL JDBC Driver not found: ");
        }
        
        return answer;
    }
    
    public int saveAnswer(UserAnswer answer) {
        int lastInsertedId = -1;
        
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                PreparedStatement stmt = conn.prepareStatement(queryInsert, Statement.RETURN_GENERATED_KEYS)) {
                stmt.setString(1, answer.getAnswer());
                stmt.setString(2, answer.getUserLogin());
                stmt.setString(3, answer.getDate());
                
                stmt.executeUpdate();

                try (ResultSet generatedKeys = stmt.getGeneratedKeys()) {
                    if (generatedKeys.next()) {
                        lastInsertedId = generatedKeys.getInt(1);
                    }
                }
            }
            catch (SQLException e) {
                System.out.println("answerSAVE: SQL Error: " + e.getMessage());
            }
        } catch (ClassNotFoundException e) {
            System.out.println("MySQL JDBC Driver not found: ");
        }
        
        return lastInsertedId;
    }
    
    public List<UserAnswer> getAnswersByLogin(String login) {
        List<UserAnswer> answers = new ArrayList<>();
        
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                PreparedStatement stmt = conn.prepareStatement(querySelectLogin)) {
                stmt.setString(1, login);

                try (ResultSet rs = stmt.executeQuery()) {
                    while (rs.next()) {
                        UserAnswer answer = new UserAnswer(
                                    rs.getInt("Id"),
                                    rs.getString("answer"),
                                    rs.getString("userLogin"),
                                    rs.getString("answerDate"));
                        answers.add(answer);
                    }
                }
            }
            catch (SQLException e) {
                System.out.println("answerLOGIN: SQL Error: " + e.getMessage());
            }
        } catch (ClassNotFoundException e) {
            System.out.println("MySQL JDBC Driver not found: ");
        }
        
        return answers;
    }
}
