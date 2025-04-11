<%-- 
    Document   : createPostPage
    Created on : 15 gru 2024, 16:03:08
    Author     : ola
--%>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="http://localhost:8080/Lista9_10/css/index.css?q=1">
        <title>Nowy post</title>
    </head>
    <body style="background-color: <%= request.getAttribute("bgColor") %>;">
        <form method="post" action="createpost" class="centerForm" style="grid-template-columns: repeat(1, minmax(200px, 1fr)); width: 250px">
            <h1 class="fullSpan">Nowy post</h1>
            <hr>
            
            <div>
            <% if (request.getAttribute("error") != null) { %>
                <h3 class="fullSpan"><%= request.getAttribute("error") %></h3>
                <hr>
            <% } %>
            </div>
            
            <div class="formPart">
                <label for="question">Treść pytania</label>
                <input name="question" id="question">

                <input type="submit" value="Dodaj post">
            </div>
            <a href="http://localhost:8080/Lista9_10/">Wróć na stronę główną</a>
        </form>
    </body>
</html>
