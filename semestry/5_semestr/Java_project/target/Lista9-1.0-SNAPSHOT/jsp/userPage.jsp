<%@page import="java.util.*"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="http://localhost:8080/Lista9_10/css/index.css?q=1">
        <link rel="stylesheet" type="text/css" href="http://localhost:8080/Lista9_10/css/postPage.css?q=1">
        <title>Profil</title>
    </head>
    <body style="background-color: <%= request.getAttribute("bgColor") %>;">
        <div class="content">
            <div class="fullSpan row">
                <div style="width: 300px">
                    <h1>Forum pytań informatycznych</h1>
                </div>
                <div style="width: 200px">
                    <h4>Liczba zalogowanych użytkowników: <%= application.getAttribute("loggedInUsers") %></h4>
                </div>
                <div style="width: 200px">
                    <h4>Liczba postów: <%= getServletContext().getAttribute("numOfAllPosts") %></h4>
                    <h4>Liczba odpowiedzi: <%= getServletContext().getAttribute("numOfAllAnswers") %></h4>
                </div>
                <div style="display: flex; flex-flow: column">
                    <c:if test="${not empty sessionScope.login}">
                        <a href="http://localhost:8080/Lista9_10/createpost">Utwórz nowy post</a>
                        <a href="http://localhost:8080/Lista9_10/logout">Wyloguj się</a>
                    </c:if>
                    <c:if test="${empty sessionScope.login}">
                        <a href="http://localhost:8080/Lista9_10/login">Zaloguj się</a>
                        <a href="http://localhost:8080/Lista9_10/register">Zarejestruj się</a>
                    </c:if>
                </div>
            </div>

            <hr class="fullSpan row">

            <div class="fullSpan row leftAlign">      
                <a href="http://localhost:8080/Lista9_10/">Wróć na stronę główną</a>
                <a href="http://localhost:8080/Lista9_10/myposts">Moje posty</a>
                <a href="http://localhost:8080/Lista9_10/myanswers">Moje odpowiedzi</a>
            </div>
            
            
            <div class="fullSpan">
                <h3>Login: ${sessionScope.login}</h3>
                <h3>Postów dodałeś/aś: <%= request.getAttribute("numUserPosts") %></h3>
                <h3>Odpowiedzi napisałeś/aś <%= request.getAttribute("numUserAnswers") %>;</h3>
            </div>
        </div>
    </body>
</html>
