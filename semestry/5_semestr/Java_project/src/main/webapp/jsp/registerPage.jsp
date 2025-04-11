<%-- 
    Document   : filesList
    Created on : 23 lis 2024, 10:33:47
    Author     : ola
--%>

<%@page import="java.util.*"%>
<%@page contentType="text/html" pageEncoding="UTF-8"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="http://localhost:8080/Lista9_10/css/index.css?q=1">
        <title>Rejestracja</title>
    </head>
    <body style="background-color: <%= request.getAttribute("bgColor") %>;">
        <form method="post" action="register" class="centerForm" style="grid-template-columns: repeat(1, minmax(200px, 1fr)); width: 250px">
            <h1 class="fullSpan">Zarejestruj się</h1>
            <hr>
            <div>
            <c:forEach var="error" items="${errors}">
                <h3>${error}</h3>
            </c:forEach>
                <% if (request.getAttribute("error") != null) { %> <hr> <% } %>
            </div>
            
            <div class="formPart">
                <label for="login">Login</label>
                <input name="login" id="login">

                <label for="password">Hasło</label>
                <input name="password" id="password" type="password">

                <label for="repeatPassword">Powtórz Hasło</label>
                <input name="repeatPassword" id="repeatPassword" type="password">

                <input type="submit" value="Zarejestruj się">
            </div>
            <hr>
            <a href="http://localhost:8080/Lista9_10/" style="display: inline" class="fullSpan">Wróć na stronę główną</a>
            <a href="http://localhost:8080/Lista9_10/login" style="display: inline" class="fullSpan">Zaloguj się</a>
        </form>
    </body>
</html>

