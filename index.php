<?php 

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
        <title>Home</title>
        <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
        <script src="./src/jquery-3.6.4.min.js"></script>
        <script>
            $(document).ready(function(){
                $("#btnClick").click(function(){
                    $("#myPara").css("background-color", "green");
                });
            });
        </script>
    </head>
    <body> 
          
    <nav class=”navbar navbar-expand-lg navbar-light bg-light”>

        <div class=”container-fluid”>
        <a class=”navbar-brand” href=”#”>My Website</a>
        <button class=”navbar-toggler” type=”button” data-bs-toggle=”collapse” data-bs-target=”#navbarNav” aria-controls=”navbarNav” aria-expanded=”false” aria-label=”Toggle navigation”>
            <span class=”navbar-toggler-icon”></span>
        </button>
        <div class=”collapse navbar-collapse” id=”navbarNav”>
            <ul class=”navbar-nav”>
            <li class=”nav-item”>
                <a class=”nav-link active” aria-current=”page” href=”#”>Home</a>
            </li>
            <li class=”nav-item”>
                <a class=”nav-link” href='/search.php'>Search</a>
            </li>
            <li class=”nav-item”>
                <a class=”nav-link” href='/register.php'>Register</a>
            </li>
            </ul>
        </div>
        </div>
        </nav>


        <p id="myPara">Start</p>             
        <button id="btnClick">Background</button>
        <main class="container">            
            <h1>Home</h1>
            <a href="/search.php">Search</a><br><br>
            <a href="/register.php">Register</a>
        </main>
    </body>
</html>