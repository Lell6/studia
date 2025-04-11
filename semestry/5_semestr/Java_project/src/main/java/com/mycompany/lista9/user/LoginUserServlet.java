/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/Servlet.java to edit this template
 */
package com.mycompany.lista9.user;

import java.io.*;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

/**
 *
 * @author ola
 */
public class LoginUserServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        
        HttpSession session = request.getSession();
        String loggedUserLogin = (String) session.getAttribute("login");
        if (loggedUserLogin != null && !loggedUserLogin.isEmpty()) {
            response.sendRedirect("");
            return;
        }
        
        request.getRequestDispatcher("/jsp/loginPage.jsp").forward(request, response);
    }

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        HttpSession session = request.getSession();
        String loggedUserLogin = (String) session.getAttribute("login");

        if (loggedUserLogin != null && !loggedUserLogin.isEmpty()) {
            response.sendRedirect("");
            return;
        }

        String login = (String) request.getParameter("login");
        if (login != null && !login.isEmpty()) {
            session.setAttribute("login", login);
            response.sendRedirect("");
        } else {
            response.sendRedirect("login");
        }
    }
}
