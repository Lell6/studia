<%@page import="java.util.List"%>
<%@ page import="com.mycompany.lista9.clases_models.UserAnswer" %>
<%@ page contentType="text/html" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Post</title>
        <link rel="stylesheet" type="text/css" href="http://localhost:8080/Lista9_10/css/index.css?q=1">
        <link rel="stylesheet" type="text/css" href="http://localhost:8080/Lista9_10/css/postPage.css?q=1">
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
                <c:if test="${not empty sessionScope.login}">
                    <a id="addAnswerButton">Dodaj odpowiedź</a>                  
                    <div>
                    <% if (request.getAttribute("error") != null) { %>
                        <h3 class="fullSpan"><%= request.getAttribute("error") %></h3>
                        <hr>
                    <% } %>
                    </div>   
                </c:if>
            </div>

            <c:if test="${not empty sessionScope.login}">
                <div class="fullSpan" id="addAnswerForm" style="display: none">                        
                    <form method="post" action="createanswer?questionId=${post.getId()}">
                        <div class="formPart" style="width: 300px">
                            <label for="answer">Treść odpowiedzi</label>
                            <input name="answer" id="answer">

                            <input type="submit" value="Dodaj">
                        </div>
                    </form>
                </div>
            </c:if>

            <div class="fullSpan post">            
                <h2>Post: ${post.getQuestion()}</h2>
                <p>Uzytkownik: ${post.getUserLogin()}<br>Data dodania: ${post.getDate()}</p>
                <%
                    List<UserAnswer> answers = (List<UserAnswer>) request.getAttribute("answers");
                    if (answers != null && !answers.isEmpty()) { 
                %>
                    <ul>
                        <% for (UserAnswer answer : answers) { %>
                            <hr>
                            <li>
                                <h3>Użytkownik <%= answer.getUserLogin() %></h3>
                                <p class="date">Data odpowiedzi: <%= answer.getDate() %></p>
                                <p class="answerContent"><%= answer.getAnswer() %></p>
                            </li>
                        <% } %>
                    </ul>
                <% } else { %>
                    <p>Brak odpowiedzi na to post.</p>
                <% } %>
            </div>
        </div>
        
        <script>
            const addAnswerButton = document.getElementById("addAnswerButton");
            const addAnswerForm = document.getElementById("addAnswerForm");
            
            addAnswerButton.addEventListener('click', (event) => {
               if (addAnswerForm.style.display === 'none') {
                   addAnswerForm.style.display = 'block';
                   event.target.innerText = "Ukryj dodawanie";
               } else {
                   addAnswerForm.style.display = 'none';
                   event.target.innerText = "Dodaj odpowiedź";
               }
            });
        </script>
    </body>
</html>