/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/Servlet.java to edit this template
 */
package com.mycompany.lista9.user;

import com.mycompany.lista9.answer.AnswerRepository;
import com.mycompany.lista9.clases_models.UserAnswer;
import com.mycompany.lista9.clases_models.UserPost;
import com.mycompany.lista9.post.PostRepository;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.List;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

/**
 *
 * @author ola
 */
public class UserProfileServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {        
        HttpSession session = request.getSession();
        String loggedUserLogin = (String) session.getAttribute("login");
        if (loggedUserLogin == null || loggedUserLogin.isEmpty()) {
            response.sendRedirect("");
            return;
        }
        
        PostRepository postRepository = new PostRepository();        
        List<UserPost> posts = postRepository.getPostsByLogin(loggedUserLogin);
        AnswerRepository answerRepository = new AnswerRepository();
        List<UserAnswer> answers = answerRepository.getAnswersByLogin(loggedUserLogin);        
        
        request.setAttribute("numUserPosts", posts.size());
        request.setAttribute("numUserAnswers", answers.size());

        request.getRequestDispatcher("/jsp/userPage.jsp").forward(request, response);
    }
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
    }

    /**
     * Returns a short description of the servlet.
     *
     * @return a String containing servlet description
     */
    @Override
    public String getServletInfo() {
        return "Short description";
    }// </editor-fold>
}
