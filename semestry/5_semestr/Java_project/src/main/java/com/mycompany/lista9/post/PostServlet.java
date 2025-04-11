/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/Servlet.java to edit this template
 */
package com.mycompany.lista9.post;

import com.mycompany.lista9.answer.AnswerRepository;
import com.mycompany.lista9.answer.ListOfAnswersRepository;
import com.mycompany.lista9.clases_models.ListOfAnswers;
import com.mycompany.lista9.clases_models.UserAnswer;
import com.mycompany.lista9.clases_models.UserPost;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class PostServlet extends HttpServlet {
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        doGet(request, response);
    }
    
    
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        
        String id = request.getParameter("id");
        if (id == null || id.isEmpty()) {
            response.sendRedirect("");
        }
        PostRepository postRepository = new PostRepository();        
        UserPost post = postRepository.getPostById(Integer.parseInt(id));
        
        if (post == null) {
            response.sendRedirect("");
            return;
        }
        
        request.setAttribute("post", post);
        
        AnswerRepository answerRespository = new AnswerRepository();        
        List<UserAnswer> answers = answerRespository.getAnswerByPost(post.getId());
        
        request.setAttribute("answers", answers);
        request.getRequestDispatcher("/jsp/postPage.jsp").forward(request, response);
    }
}
