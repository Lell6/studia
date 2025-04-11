/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/Servlet.java to edit this template
 */
package com.mycompany.lista9.post;


import com.mycompany.lista9.clases_models.UserPost;
import java.io.IOException;
import java.io.PrintWriter;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

/**
 *
 * @author ola
 */
public class CreatePostServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {        
        
        HttpSession session = request.getSession();
        String loggedUserLogin = (String) session.getAttribute("login");
        if (loggedUserLogin == null || loggedUserLogin.isEmpty()) {
            response.sendRedirect("");
            return;
        }
        
        request.getRequestDispatcher("/jsp/createPostPage.jsp").forward(request, response);
    }

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {      
        
        HttpSession session = request.getSession();
        String loggedUserLogin = (String) session.getAttribute("login");
        if (loggedUserLogin == null || loggedUserLogin.isEmpty()) {
            response.sendRedirect("");
            return;
        }
        
        String question = request.getParameter("question");
        
        if (question.isEmpty()) {
            request.setAttribute("error", "Puste pola");
            request.getRequestDispatcher("/jsp/createPostPage.jsp").forward(request, response);
            return;
        }
        
        UserPost newPost = new UserPost(-1, question, loggedUserLogin, 0, null);
        PostRepository repo = new PostRepository();
        repo.savePost(newPost);
        
        response.sendRedirect("");
    }
}
