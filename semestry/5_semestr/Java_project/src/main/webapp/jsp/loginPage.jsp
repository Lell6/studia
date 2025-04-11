<%@page import="java.util.*"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <title>Logowanie</title>
        <link rel="stylesheet" type="text/css" href="http://localhost:8080/Lista9_10/css/index.css?q=1">
    </head>
    <body style="background-color: <%= request.getAttribute("bgColor") %>;">
        <form method="post" action="login" class="centerForm" style="grid-template-columns: repeat(1, minmax(200px, 1fr)); width: 250px">
            <h1 class="fullSpan">Zaloguj się</h1>
            <hr>
            <div>
            <% if (request.getAttribute("error") != null) { %>
                <h3 class="fullSpan"><%= request.getAttribute("error") %></h3>
                <hr>
            <% } %>
            </div>

            <div class="formPart">
                <label for="login">Login</label>
                <input name="login" id="login">

                <label for="password">Hasło</label>
                <input name="password" id="password" type="password">

                <input type="submit" value="Zaloguj się">
            </div>
            <hr>
            <a href="http://localhost:8080/Lista9_10/" style="display: inline" class="fullSpan">Wróć na stronę główną</a>
            <a href="http://localhost:8080/Lista9_10/register" style="display: inline" class="fullSpan">Zarejestruj się</a>
        </form>
    </body>
</html>
