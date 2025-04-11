<%@page import="java.util.*"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="http://localhost:8080/Lista9_10/css/index.css?q=1">
        <link rel="stylesheet" type="text/css" href="http://localhost:8080/Lista9_10/css/postPage.css?q=1">
        <title>Twoje pytania</title>
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

            <div class="fullSpan row leftAlign" style="justify-content: left">    
                <h1>Twoje posty</h1>
                <a href="http://localhost:8080/Lista9_10/">Wróć na stronę główną</a>   
            </div>

            <c:set var="numToDisplay" value="${posts.size()}" />        
            <c:if test="${numToDisplay == 0}">
                <h3>Brak postów - dodaj nowy</h3>
                <a href="http://localhost:8080/Lista9_10/createpost">Utwórz nowy post</a>
            </c:if>

            <c:if test="${numToDisplay > 0}">                
                <c:if test="${numToDisplay > 2}">
                    <ul class="fullSpan postList scrollable" style="height: 440px;">
                </c:if>
                <c:if test="${numToDisplay <= 2}">
                    <ul class="fullSpan postList">
                </c:if>            
                    <c:forEach var="i" begin="0" end="${numToDisplay-1}" step="1">
                        <li class="separate">
                            <h3>Post ${posts[i].getId()}: ${posts[i].getQuestion()}</h3>
                            <h4>Data dodania: ${posts[i].getDate()}</h4>
                            <h4>Liczba udzielonych odpowiedzi: ${posts[i].getNumberOfAnswers()}</h4>
                            <a href="http://localhost:8080/Lista9_10/post?id=${posts[i].getId()}" class="postLink">Zobacz więcej</a>
                        </li>
                    </c:forEach>
                </ul>
            </c:if>  
        </div>
    </body>
</html>
