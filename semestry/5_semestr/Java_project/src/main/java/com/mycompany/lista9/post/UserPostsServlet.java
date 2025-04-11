package com.mycompany.lista9.post;

import com.mycompany.lista9.answer.AnswerRepository;
import com.mycompany.lista9.answer.ListOfAnswersRepository;
import com.mycompany.lista9.clases_models.ListOfAnswers;
import com.mycompany.lista9.clases_models.UserAnswer;
import com.mycompany.lista9.clases_models.UserPost;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.List;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

public class UserPostsServlet extends HttpServlet {
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
        
        request.setAttribute("posts", posts);
        request.getRequestDispatcher("/jsp/userPosts.jsp").forward(request, response);
    }
    
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
    }
}
