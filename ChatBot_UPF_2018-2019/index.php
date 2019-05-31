<?php
    if (!isset($_SESSION)) {
        session_start();
    }
?>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ChatBot Meteo</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

        <link rel="stylesheet" href="style2.css"/>
        
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="java.js"></script>
    </head>
    <body>
        <h1>Welcome to my Weather Chatbot !</h1>
        <div id ="scrollAuto" class="container-text">
            <?php
                //tous les messages
                require_once("assets/fonctions.php");
                if (isset($_POST["reset"])) {
                    session_destroy();
                }
                if (isset($_POST["query"])) {
                    if (!isset($_SESSION["message_user"])) {
                        $_SESSION["message_user"] = array();
                        $_SESSION["message_bot"] = array();
                    }
                    $local_array_1 = $_SESSION["message_user"];
                    array_push($local_array_1, $_POST["query"]);
                    $_SESSION["message_user"] = $local_array_1;
                    
                    $listLuis = queryLuis($_POST["query"]);
                    
                    $local_array_2 = $_SESSION["message_bot"];
                    array_push($local_array_2, botReply($listLuis, $listJour, $codeWeather));
                    $_SESSION["message_bot"] = $local_array_2;
                    $compteur = sizeof($_SESSION["message_user"]);

                    $a = $_SESSION["message_user"];
                    $b = $_SESSION["message_bot"];
                    for ($i=0; $i < $compteur; $i++) { 
                        afficheMessageUser($a[$i]);
                        afficheMessageBot($b[$i]);
                    }
                }
            ?>
        </div>
        <div class="container-input">
            <form action="#" method="post">
                <input type="text" name="query" placeholder="Envoyer un message" class="input_message" autocomplete="off" autofocus/>
                <input type="submit" value=" " class="submit"/>
            </form>
             <form action="#" method="post">
            <input type="hidden" name="reset" value="Reset" >
            <input type="submit" value=" " id="reset">
        </form>
        </div>
       
    </body>
</html>