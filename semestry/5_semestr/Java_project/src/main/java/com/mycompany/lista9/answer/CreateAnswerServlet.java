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
public class CreateAnswerServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
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
        
        String answer = request.getParameter("answer");
        String questionId = request.getParameter("questionId");
        
        if (answer.isEmpty() || questionId.isEmpty()) {
            request.setAttribute("error", "Puste pola");
            request.getRequestDispatcher("/post?id=" + questionId).forward(request, response);
            return;
        }
        
        UserAnswer newAnswer = new UserAnswer(-1, answer, loggedUserLogin, null);        
        AnswerRepository repo = new AnswerRepository();
        int answerId = repo.saveAnswer(newAnswer);
        
        ListOfAnswersRepository listRepo = new ListOfAnswersRepository();
        listRepo.saveAnswerToPost(Integer.parseInt(questionId), answerId);
        
        PostRepository postRepo = new PostRepository();
        postRepo.updateAnswersCount(Integer.parseInt(questionId), 1);
        
        response.setStatus(HttpServletResponse.SC_SEE_OTHER);
        response.setHeader("Location", "http://localhost:8080/Lista9_10/post?id=" + questionId);
    }
}
