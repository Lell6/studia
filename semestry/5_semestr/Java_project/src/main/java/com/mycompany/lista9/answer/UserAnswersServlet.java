/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/Servlet.java to edit this template
 */
package com.mycompany.lista9.answer;

import com.mycompany.lista9.clases_models.ListOfAnswers;
import com.mycompany.lista9.clases_models.UserAnswer;
import com.mycompany.lista9.clases_models.UserPost;
import com.mycompany.lista9.post.PostRepository;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.List;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

public class UserAnswersServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {                
        HttpSession session = request.getSession();
        String loggedUserLogin = (String) session.getAttribute("login");
        if (loggedUserLogin == null || loggedUserLogin.isEmpty()) {
            response.sendRedirect("");
            return;
        }      
        
        AnswerRepository answerRepository = new AnswerRepository();
        List<UserAnswer> answers = answerRepository.getAnswersByLogin(loggedUserLogin);
        
        request.setAttribute("answers", answers);
        request.getRequestDispatcher("/jsp/userAnswers.jsp").forward(request, response);
    }

    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
    }
}
