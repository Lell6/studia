/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/Servlet.java to edit this template
 */
package com.mycompany.lista9;

import com.mycompany.lista9.clases_models.UserPost;
import com.mycompany.lista9.post.PostRepository;
import java.io.*;
import java.util.*;
import javax.json.*;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 *
 * @author ola
 */
public class SearchServlet extends HttpServlet {    
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {  
        String searchValue = (String) request.getParameter("searchValue");  
        PostRepository repo = new PostRepository();
        
        List<UserPost> posts = repo.getPostsByQuestion(searchValue);
        
        JsonArrayBuilder jsonArrayBuilder = Json.createArrayBuilder();
        for (UserPost post : posts) {
            JsonObjectBuilder jsonObjectBuilder = Json.createObjectBuilder()
                .add("id", post.getId())
                .add("question", post.getQuestion())
                .add("date", post.getDate())
                .add("numberOfAnswers", post.getNumberOfAnswers());

            jsonArrayBuilder.add(jsonObjectBuilder);
        }
        
        JsonArray jsonArray = jsonArrayBuilder.build();        
        response.getWriter().write(jsonArray.toString());
    }

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
    }
}
