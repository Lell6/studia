<%@page import="java.util.*"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" type="text/css" href="http://localhost:8080/Lista9_10/css/index.css?q=1">
        <title>Main page</title>
    </head>
    <body style="background-color: <%= request.getAttribute("bgColor") %>;" class="content">
        <div class="contentLeft">
            <c:set var="numToDisplay" value="${numLastToDisplay}" />
            <div class="fullSpan">                
                <h4>Ostatnie posty</h4>
            </div>
            <hr class="fullSpan row" style="margin-bottom: 10px;">
            
            <c:if test="${numLastToDisplay == 0}">
                <h1>Brak postów na forum</h1>
            </c:if>

            <c:if test="${numLastToDisplay > 0}">
                <ul>
                    <c:forEach var="i" begin="0" end="${numToDisplay - 1}" step="1">
                        <li class="separate">
                            <h3>Post ${lastPosts[i].getId()}: ${lastPosts[i].getQuestion()}</h3>
                            <a href="http://localhost:8080/Lista9_10/post?id=${lastPosts[i].getId()}" class="postLink">Zobacz</a>
                        </li>
                    </c:forEach>
                </ul>
            </c:if> 
        </div>
        <div class="contentMiddle content">
            <header class="fullSpan row" style="height: 50px">
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
            </header>
            <hr class="fullSpan row">
            <form id="searchBar" class="fullSpan">
                <input name="searchValue" id="searchValue" placeholder="Szukana fraza..." style="width: 100%; height: 40px">            
                <div id="searchResult">

                </div>
            </form>
            
            <c:set var="numToDisplay" value="${posts.size() < numOfPosts ? posts.size() : numOfPosts}" />
            <div class="fullSpan">                
                <h4>Lista postów na forum</h4>
            </div>
            
            <c:if test="${numToDisplay > 2}">
                <div class="fullSpan postList scrollable" style="height: 370px;">
            </c:if>
            <c:if test="${numToDisplay <= 2}">
                <div class="fullSpan postList">
            </c:if>

                <c:if test="${numToDisplay == 0}">
                    <h1>Brak pytań na forum</h1>
                </c:if>

                <c:if test="${numToDisplay > 0}">
                    <ul>
                        <c:forEach var="i" begin="0" end="${numToDisplay - 1}" step="1">
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
            <c:if test="${not empty sessionScope.login}">
            <hr class="fullSpan row">
            <footer class="fullSpan row">
                <h3>login - ${sessionScope.login}</h3>
                <div>
                    <a href="http://localhost:8080/Lista9_10/profile">Moje konto</a>
                    <a href="http://localhost:8080/Lista9_10/myposts">Moje posty</a>
                    <a href="http://localhost:8080/Lista9_10/myanswers">Moje odpowiedzi</a>
                </div>
            </footer>
            </c:if>
        </div>
            
        <c:if test="${request.getAttribute('randomPage') != '-1'}">
        <a href="http://localhost:8080/Lista9_10/post?id=<%= request.getAttribute("randomPage") %>" class="contentRight">
            LOSOWY POST
        </a>
        </c:if>
        
        <script src="js/searchBar.js"></script>
        <script>
            const scrollableContainer = document.querySelector('.postList');

            scrollableContainer.addEventListener("scroll", (event) => {
                const scrollTop = event.target.scrollTop;
                const scrollHeight = event.target.scrollHeight;
                const clientHeight = event.target.clientHeight;

                if (scrollTop + clientHeight >= scrollHeight - 30) {
                    event.target.style.boxShadow = 'none';
                } else {
                    event.target.style.boxShadow = 'inset 0 -30px 30px -30px rgb(160, 160, 44)';
                }
            });
        </script>
    </body>
</html>

