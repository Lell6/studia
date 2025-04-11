package com.mycompany.lista9;


import javax.servlet.ServletContext;
import javax.servlet.ServletContextEvent;
import javax.servlet.ServletContextListener;

public class ContextListener implements ServletContextListener {

    @Override
    public void contextInitialized(ServletContextEvent sce) {
        ServletContext context = sce.getServletContext();

        String loggedInUsersStr = context.getInitParameter("loggedInUsers");
        int loggedInUsers = Integer.parseInt(loggedInUsersStr);

        context.setAttribute("loggedInUsers", loggedInUsers);
    }

    @Override
    public void contextDestroyed(ServletContextEvent sce) {
    }
}
