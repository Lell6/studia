package com.mycompany.lista9.user;

import com.mycompany.lista9.clases_models.User;
import java.io.*;
import java.util.*;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.sql.*;

public class RegisterUserServlet extends HttpServlet {    
    private String url = "jdbc:mysql://localhost:3306/stack_overflow";
    private String usernameDb = "root";
    private String passwordDb = "";
    
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        
        HttpSession session = request.getSession();
        String loggedUserLogin = (String) session.getAttribute("login");
        if (loggedUserLogin != null && !loggedUserLogin.isEmpty()) {
            response.sendRedirect("");
            return;
        }
        
        request.getRequestDispatcher("/jsp/registerPage.jsp").forward(request, response);
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
        String password = (String) request.getParameter("password");
        String repeatPassword = (String) request.getParameter("repeatPassword");   
        String usersFile = getServletContext().getRealPath("/WEB-INF/users.ser");
        List<String> errors = new ArrayList<>();
        
        if (!login.matches("^[a-zA-Z0-9_]{4,16}$")) {
            errors.add("Nieprawidłowy login");
        }        
        if (!password.matches("^[^\\s]{8,16}$")) {
            errors.add("Nieprawidłowe hasło");
        }      
        if (!password.equals(repeatPassword)) {
            errors.add("Hasło musi być takie same");
        }
        if (!errors.isEmpty()) {
            request.setAttribute("errors", errors);
            request.getRequestDispatcher("/jsp/registerPage.jsp").forward(request, response);
        }  
        
        String queryInsert = "INSERT INTO users (login, password) VALUES (?, ?)";
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                 PreparedStatement stmt = conn.prepareStatement(queryInsert)) {

                stmt.setString(1, login);
                stmt.setString(2, password);

                int count = stmt.executeUpdate();
            } catch (SQLException e) {
                System.err.println("SQL Error: " + e.getMessage());
                errors.add("Błąd zapytania");
                request.setAttribute("errors", errors);
                request.getRequestDispatcher("/jsp/registerPage.jsp").forward(request, response);
            }
        } catch (ClassNotFoundException e) {
            System.err.println("MySQL JDBC Driver not found: " + e.getMessage());
            errors.add("MySQL Driver not found");
            request.setAttribute("errors", errors);
            request.getRequestDispatcher("/jsp/registerPage.jsp").forward(request, response);
        }
                
        response.sendRedirect("login");
    }
}
