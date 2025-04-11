/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.lista9.post;

import com.mycompany.lista9.answer.AnswerRepository;
import com.mycompany.lista9.answer.ListOfAnswersRepository;
import com.mycompany.lista9.clases_models.ListOfAnswers;
import com.mycompany.lista9.clases_models.UserAnswer;
import com.mycompany.lista9.clases_models.UserPost;
import java.io.*;
import java.sql.*;
import java.util.*;
import javax.servlet.ServletContext;

/**
 *
 * @author ola
 */
public class PostRepository {
    private String queryInsert = "INSERT INTO posts (question, userLogin, date) VALUES (?,?,?)";    
    private String queryUpdatePost = "UPDATE posts SET question = ? WHERE Id = ?";        
    private String queryUpdateAnswersCount = "UPDATE posts SET answersCount = answersCount + ? WHERE Id = ?";    

    private String queryDelete = "DELETE posts WHERE Id = ?";
    
    private String querySelectAll = "SELECT * FROM posts";    
    private String querySelectId = "SELECT * FROM posts WHERE Id = ?";    
    private String querySelectLogin =  "SELECT * FROM posts WHERE posts.userLogin = ?";
    private String querySelectQuestion = "SELECT * FROM posts WHERE posts.question LIKE ?";
    
    private String url = "jdbc:mysql://localhost:3306/stack_overflow";
    private String usernameDb = "root";
    private String passwordDb = "";
    
    public List<UserPost> getPosts() {
        List<UserPost> posts = new ArrayList<>();
        
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                PreparedStatement stmt = conn.prepareStatement(querySelectAll)) {

                try (ResultSet rs = stmt.executeQuery()) {
                    while (rs.next()) {
                        UserPost post = new UserPost(
                                rs.getInt("Id"), 
                                rs.getString("question"), 
                                rs.getString("userLogin"), 
                                rs.getInt("answersCount"),
                                rs.getString("date"));
                        
                        posts.add(post);
                    }
                }
            }
            catch (SQLException e) {
                System.out.println("postALL: SQL Error: " + e.getMessage());
                return null;
            }
        } catch (ClassNotFoundException e) {
            System.out.println("MySQL JDBC Driver not found: ");
            return null;
        }
        
        return posts;
    }
    
    public UserPost getPostById(int id) {
        UserPost post = null;
        List<UserAnswer> answers = new ArrayList<>();
        UserAnswer answer = null;
        
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                PreparedStatement stmt = conn.prepareStatement(querySelectId)) {
                stmt.setInt(1, id);

                try (ResultSet rs = stmt.executeQuery()) {
                    if (rs.next()) {
                        post = new UserPost(
                                rs.getInt("Id"), 
                                rs.getString("question"), 
                                rs.getString("userLogin"), 
                                rs.getInt("answersCount"),
                                rs.getString("date"));
                    }
                }
            }
            catch (SQLException e) {
                System.out.println("postID: SQL Error: " + e.getMessage());
                return null;
            }
        } catch (ClassNotFoundException e) {
            System.out.println("MySQL JDBC Driver not found: ");
            return null;
        }
        
        return post;
    }
    
    public List<UserPost> getPostsByLogin(String login) {
        List<UserPost> posts = new ArrayList<>();
        
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                PreparedStatement stmt = conn.prepareStatement(querySelectLogin)) {
                
                stmt.setString(1, login);

                try (ResultSet rs = stmt.executeQuery()) {
                    while (rs.next()) {
                        UserPost post = new UserPost(
                                rs.getInt("Id"), 
                                rs.getString("question"), 
                                rs.getString("userLogin"), 
                                rs.getInt("answersCount"),
                                rs.getString("date"));
                        
                        posts.add(post);
                    }
                }
            }
            catch (SQLException e) {
                System.out.println("postLOGIN: SQL Error: " + e.getMessage());
                return null;
            }
        } catch (ClassNotFoundException e) {
            System.out.println("MySQL JDBC Driver not found: ");
            return null;
        }
        
        return posts;
    }
    
    public List<UserPost> getPostsByQuestion(String searchValue) {
        List<UserPost> posts = new ArrayList<>();
                
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                PreparedStatement stmt = conn.prepareStatement(querySelectQuestion)) {
                
                stmt.setString(1,  "%" + searchValue + "%");

                try (ResultSet rs = stmt.executeQuery()) {
                    while (rs.next()) {
                        UserPost post = new UserPost(
                                rs.getInt("Id"), 
                                rs.getString("question"), 
                                rs.getString("userLogin"), 
                                rs.getInt("answersCount"),
                                rs.getString("date"));
                        
                        posts.add(post);
                    }
                }
            }
            catch (SQLException e) {
                System.out.println("postLOGIN: SQL Error: " + e.getMessage());
                return null;
            }
        } catch (ClassNotFoundException e) {
            System.out.println("MySQL JDBC Driver not found: ");
            return null;
        }
        
        return posts;
    }
    
    public void savePost(UserPost post) {
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                PreparedStatement stmt = conn.prepareStatement(queryInsert)) {
                stmt.setString(1, post.getQuestion());
                stmt.setString(2, post.getUserLogin());
                stmt.setString(3, post.getDate());

                int count = stmt.executeUpdate();
            }
            catch (SQLException e) {
                System.out.println("postSAVE: SQL Error: " + e.getMessage());
            }
        } catch (ClassNotFoundException e) {
            System.out.println("MySQL JDBC Driver not found: ");
        }
    }
    
    public void updateAnswersCount(int id, int count) {
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                PreparedStatement stmt = conn.prepareStatement(queryUpdateAnswersCount)) {
                stmt.setInt(1, count);
                stmt.setInt(2, id);

                int rowsAffected = stmt.executeUpdate();
                System.out.println(rowsAffected);
            }
            catch (SQLException e) {
                System.out.println("postSAVE: SQL Error: " + e.getMessage());
            }
        } catch (ClassNotFoundException e) {
            System.out.println("MySQL JDBC Driver not found: ");
        }
    }
}
