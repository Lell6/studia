/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/Servlet.java to edit this template
 */
package com.mycompany.lista9;

import com.mycompany.lista9.answer.AnswerRepository;
import com.mycompany.lista9.answer.ListOfAnswersRepository;
import com.mycompany.lista9.clases_models.ListOfAnswers;
import com.mycompany.lista9.clases_models.UserAnswer;
import com.mycompany.lista9.post.PostRepository;
import com.mycompany.lista9.clases_models.UserPost;
import java.io.*;
import java.util.*;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 *
 * @author ola
 */
public class MainServlet extends HttpServlet {    
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        PostRepository postRepository = new PostRepository();
        List<UserPost> posts = postRepository.getPosts();
        
        AnswerRepository repo = new AnswerRepository();
        List<UserAnswer> answers = repo.getAnswersAll();
        
        int n = 3;
        int size = (posts == null) ? 0 : posts.size();
        int sizeAnswer = (answers == null) ? 0 : answers.size();
        
        List<UserPost> lastPosts;
        lastPosts = null;   

        if (size < n) {
            n = size;
        }     
        
        if (size != 0) {
            if (n >= size) {
                lastPosts = new ArrayList<>(posts);
            } else {
                lastPosts = new ArrayList<>(posts.subList(size - n, size));
            }
        }
        
        Random rand = new Random();
        int randomPage = -1;        
        if (size != 0) {
            randomPage = rand.nextInt(posts.size() + 1 - 1) + 1;
        }
        
        response.setHeader("Content-Type", "text/html; charset=UTF-8");
        
        request.setAttribute("numOfPosts", Integer.valueOf(getServletContext().getInitParameter("numOfPosts")));
        request.setAttribute("posts", posts);        
        request.setAttribute("lastPosts", lastPosts);
        request.setAttribute("numLastToDisplay", n);
        request.setAttribute("randomPage", randomPage);
        getServletContext().setAttribute("numOfAllAnswers", sizeAnswer);        
        getServletContext().setAttribute("numOfAllPosts", size);
        request.getRequestDispatcher("/jsp/index.jsp").forward(request, response);
    }
    
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
    }
}
