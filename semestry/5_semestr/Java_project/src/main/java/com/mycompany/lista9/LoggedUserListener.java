package com.mycompany.lista9;

import javax.servlet.ServletContext;
import javax.servlet.http.HttpSessionAttributeListener;
import javax.servlet.http.HttpSessionBindingEvent;
import java.util.logging.Logger;

public class LoggedUserListener implements HttpSessionAttributeListener {

    private static final Logger LOGGER = Logger.getLogger(LoggedUserListener.class.getName());

    @Override
    public void attributeAdded(HttpSessionBindingEvent event) {
        if ("login".equals(event.getName())) {
            ServletContext context = event.getSession().getServletContext();
            Integer loggedInUsers = (Integer) context.getAttribute("loggedInUsers");

            if (loggedInUsers == null) {
                loggedInUsers = 0;
            }

            loggedInUsers++;
            context.setAttribute("loggedInUsers", loggedInUsers);

            LOGGER.info("User logged in: " + event.getValue());
            LOGGER.info("Total logged-in users: " + loggedInUsers);
        }
    }

    @Override
    public void attributeRemoved(HttpSessionBindingEvent event) {
        if ("login".equals(event.getName())) {
            ServletContext context = event.getSession().getServletContext();
            Integer loggedInUsers = (Integer) context.getAttribute("loggedInUsers");

            if (loggedInUsers != null && loggedInUsers > 0) {
                loggedInUsers--;
                context.setAttribute("loggedInUsers", loggedInUsers);
            }

            LOGGER.info("User logged out: " + event.getValue());
            LOGGER.info("Total logged-in users: " + loggedInUsers);
        }
    }

    @Override
    public void attributeReplaced(HttpSessionBindingEvent event) {
    }
}
