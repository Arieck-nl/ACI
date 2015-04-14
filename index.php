<?php
//initial setup
require_once 'init.php';
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>ACI</title>
    <link rel="stylesheet" type="text/css" href="libs/css/style.css">
    <script src="libs/js/jquery-2.1.3.js"></script>
</head>
<body>
<div id="overlay">
    <div id="keyword-form">
        <form method="post">
            <input id="keyword" class="input keyword" type="text" placeholder="keyword" autofocus/>
            <input id="submit" type="submit"/>
        </form>
    </div>
</div>
<script src="libs/js/keyword.js"></script>
<script src="libs/js/init.js"></script>
</body>
</html>