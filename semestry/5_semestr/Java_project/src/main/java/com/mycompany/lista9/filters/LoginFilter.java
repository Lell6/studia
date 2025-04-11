/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/Filter.java to edit this template
 */
package com.mycompany.lista9.filters;

import com.mycompany.lista9.clases_models.LogEntry;
import com.mycompany.lista9.clases_models.User;
import java.io.*;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import javax.servlet.Filter;
import javax.servlet.FilterChain;
import javax.servlet.FilterConfig;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import javax.servlet.http.HttpServletRequest;
import java.sql.*;

/**
 *
 * @author ola
 */
public class LoginFilter implements Filter {
    
    private static final boolean debug = true;

    // The filter configuration object we are associated with.  If
    // this value is null, this filter instance is not currently
    // configured.
    private FilterConfig filterConfig = null;
    
    public LoginFilter() {
    }
    
    private void doBeforeProcessing(ServletRequest request, ServletResponse response)
            throws IOException, ServletException {
        if (debug) {
            log("LoginFilter:DoBeforeProcessing");
        }

        // Write code here to process the request and/or response before
        // the rest of the filter chain is invoked.
        // For example, a logging filter might log items on the request object,
        // such as the parameters.
        /*
	for (Enumeration en = request.getParameterNames(); en.hasMoreElements(); ) {
	    String name = (String)en.nextElement();
	    String values[] = request.getParameterValues(name);
	    int n = values.length;
	    StringBuffer buf = new StringBuffer();
	    buf.append(name);
	    buf.append("=");
	    for(int i=0; i < n; i++) {
	        buf.append(values[i]);
	        if (i < n-1)
	            buf.append(",");
	    }
	    log(buf.toString());
	}
         */
    }
    
    private void doAfterProcessing(ServletRequest request, ServletResponse response)
            throws IOException, ServletException {
        if (debug) {
            log("LoginFilter:DoAfterProcessing");
        }

        // Write code here to process the request and/or response after
        // the rest of the filter chain is invoked.
        // For example, a logging filter might log the attributes on the
        // request object after the request has been processed.
        /*
	for (Enumeration en = request.getAttributeNames(); en.hasMoreElements(); ) {
	    String name = (String)en.nextElement();
	    Object value = request.getAttribute(name);
	    log("attribute: " + name + "=" + value.toString());

	}
         */
        // For example, a filter might append something to the response.
        /*
	PrintWriter respOut = new PrintWriter(response.getWriter());
	respOut.println("<P><B>This has been appended by an intrusive filter.</B>");
         */
    }
    
    public void addNewEntryLog(LogEntry entry, String logFilePath) {        
        String entryToFile = entry.toString();
        try (BufferedWriter writer = new BufferedWriter(new FileWriter(logFilePath, true))) {
            writer.write(entryToFile);
            writer.newLine();
        } catch (IOException e) {
            System.err.println("Error writing to log file: " + e.getMessage());
        }
    }
    
    public void sendErrorResponse(ServletRequest request, ServletResponse response) throws IOException, ServletException {
        request.setAttribute("error", "Nieprawidłowy login lub hasło");
        request.getRequestDispatcher("/jsp/loginPage.jsp").forward(request, response);
    }

    public void doFilter(ServletRequest request, ServletResponse response,
            FilterChain chain)
            throws IOException, ServletException {   
        
        if (!(request instanceof HttpServletRequest)) {
            request.getRequestDispatcher("/jsp/loginPage.jsp").forward(request, response);
            return;
        }        
        HttpServletRequest httpRequest = (HttpServletRequest) request;
        
        if ("GET".equalsIgnoreCase(httpRequest.getMethod())) {
            chain.doFilter(request, response);
            return;
        }
        
        String login = request.getParameter("login");
        String password = request.getParameter("password"); 
        String logFile = request.getServletContext().getRealPath("/WEB-INF/loginLogs.log");
        
        LogEntry entry = new LogEntry("", "0.0.0.0");
        
        String ip = httpRequest.getHeader("X-Forwarded-For");
        if (ip == null || ip.isEmpty() || "unknown".equalsIgnoreCase(ip)) {
            ip = httpRequest.getRemoteAddr();
            entry.setUserIp(ip);
        }
        
        if (!login.matches("^[a-zA-Z0-9_]{4,16}$")) {
            entry.setMessage("Incorrect login format ");
        }        
        if (!password.matches("^[^\\s]{8,16}$")) {
            entry.setMessage(entry.getMessage() + "Incorrect password format");
        } 
        
        if (!entry.getMessage().isEmpty()) {
            addNewEntryLog(entry, logFile);
            sendErrorResponse(request, response);
            return;
        }
        
        String url = "jdbc:mysql://localhost:3306/stack_overflow";
        String usernameDb = "root";
        String passwordDb = "";
        String queryInsert = "SELECT * FROM users WHERE login = ?";
        
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            try (Connection conn = DriverManager.getConnection(url, usernameDb, passwordDb);
                 PreparedStatement stmt = conn.prepareStatement(queryInsert)) {

                stmt.setString(1, login);
                try (ResultSet rs = stmt.executeQuery()) {
                    if (rs.next()) { 
                        String passwordUser = rs.getString("password");
                        if (!passwordUser.equals(password)) {                        
                            entry.setMessage("Attempted to login to '" + login + "' with incorrect password");
                            addNewEntryLog(entry, logFile);
                            sendErrorResponse(request, response);
                            return;
                        }

                        request.setAttribute("login", login);        
                        chain.doFilter(request, response);
                    } else {
                        entry.setMessage("User with '" + login + "' login was not found");
                        addNewEntryLog(entry, logFile);
                        sendErrorResponse(request, response);
                    }
                }
            }
            catch (SQLException e) {
                System.err.println("SQL Error: "
                                   + e.getMessage());
            }
        } catch (ClassNotFoundException e) {
            entry.setMessage("MySQL JDBC Driver not found: ");
            addNewEntryLog(entry, logFile);
            sendErrorResponse(request, response);
            return;
        }
    }

    /**
     * Return the filter configuration object for this filter.
     */
    public FilterConfig getFilterConfig() {
        return (this.filterConfig);
    }

    /**
     * Set the filter configuration object for this filter.
     *
     * @param filterConfig The filter configuration object
     */
    public void setFilterConfig(FilterConfig filterConfig) {
        this.filterConfig = filterConfig;
    }

    /**
     * Destroy method for this filter
     */
    public void destroy() {
    }

    /**
     * Init method for this filter
     */
    public void init(FilterConfig filterConfig) {
        this.filterConfig = filterConfig;
        if (filterConfig != null) {
            if (debug) {
                log("LoginFilter:Initializing filter");
            }
        }
    }

    /**
     * Return a String representation of this object.
     */
    @Override
    public String toString() {
        if (filterConfig == null) {
            return ("LoginFilter()");
        }
        StringBuffer sb = new StringBuffer("LoginFilter(");
        sb.append(filterConfig);
        sb.append(")");
        return (sb.toString());
    }
    
    private void sendProcessingError(Throwable t, ServletResponse response) {
        String stackTrace = getStackTrace(t);
        
        if (stackTrace != null && !stackTrace.equals("")) {
            try {
                response.setContentType("text/html");
                PrintStream ps = new PrintStream(response.getOutputStream());
                PrintWriter pw = new PrintWriter(ps);
                pw.print("<html>\n<head>\n<title>Error</title>\n</head>\n<body>\n"); //NOI18N

                // PENDING! Localize this for next official release
                pw.print("<h1>The resource did not process correctly</h1>\n<pre>\n");
                pw.print(stackTrace);
                pw.print("</pre></body>\n</html>"); //NOI18N
                pw.close();
                ps.close();
                response.getOutputStream().close();
            } catch (Exception ex) {
            }
        } else {
            try {
                PrintStream ps = new PrintStream(response.getOutputStream());
                t.printStackTrace(ps);
                ps.close();
                response.getOutputStream().close();
            } catch (Exception ex) {
            }
        }
    }
    
    public static String getStackTrace(Throwable t) {
        String stackTrace = null;
        try {
            StringWriter sw = new StringWriter();
            PrintWriter pw = new PrintWriter(sw);
            t.printStackTrace(pw);
            pw.close();
            sw.close();
            stackTrace = sw.getBuffer().toString();
        } catch (Exception ex) {
        }
        return stackTrace;
    }
    
    public void log(String msg) {
        filterConfig.getServletContext().log(msg);
    }
    
}
