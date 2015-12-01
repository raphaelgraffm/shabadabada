<?php
    // Start the session
session_start();
?>

<!DOCTYPE html>

<html lang="fr">
    <head>
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        <title>Shabadabada</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
    </head>

    <body class="w3-blue">
        <header class="w3-container w3-red">
            <div class="w3-opennav w3-xlarge" onclick="w3_react()" style="margin-bottom:0px;padding-bottom:0px">Règles</div>
            <h1 class="w3-center w3-xxlarge w3-text-white"i style="margin-top:0px;padding-top:0px">Shabadabada</h1>
        </header>

        <nav class="w3-sidenav w3-blue w3-card-2" style="display:none;opacity:0.8;width:400px">
            <div class="w3-padding">
                <p>
                    Les joueurs se répartissent en deux équipes.
                </p>
                <p>
                    Cliquez sur le bouton "Nouveau couple" pour générer un couple de mots français/anglais. <br/>
                    Chaque équipe, à tour de rôle, doit chanter un extrait de chanson ou bien déclamer un poème ou
                    une comptine, contenant explicitement l'un des mots proposés (les verbes à l'infinitif peuvent
                    être conjugués).
                </p>
                <p>
                    Quand une équipe bloque trop longtemps, l'autre équipe démarre un compte à rebours de 10 jusqu'à 0.
                    Quand le compte à rebours est terminé, l'équipe qui a compté marque un point et un nouveau couple de mots est généré.
                </p>
                <p>
                    La première équipe à atteindre 6 points gagne.
                </p>
            </div>
        </nav>

        <section class="w3-container" style="margin-left:64px;margin-right:64px">

        <?php
            $servername = "127.0.0.1";
            $username = "root";
            $password = "database";
            $dbname = "chabadabada";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Un problème est survenu : " . $conn->connect_error);
            } 

            // get the number of couples
            $sql = "SELECT COUNT(*) FROM couples";
            $number = $conn->query($sql);
            if ($number->num_rows > 0) {
                $number = (int) $number->fetch_assoc()["COUNT(*)"];
            }

            // boolean to store the correctness
            $correct = false;
            $counter = $number;

            $random = 0;

            // loop to determine the couple
            while (!($correct))
            {
                // get a random number
                $random = rand(1,$number);


                // verify it is not in $_SESSION
                if (!(in_array($random, $_SESSION)))
                {
                    $correct = true;
                    $key = "id" . $random;
                    $_SESSION[$key] = $random;
                    $_SESSION["last"] = $random; 
                }

                $counter-=1;

                if ($counter == 0)
                {
                    $last = $_SESSION["last"];
                    session_unset();
                    $_SESSION["last"] = $last;
                    $counter = $number;
                }
            }

            // get the words 
            $sql = "SELECT * FROM couples where id=" . $random;
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $result = $result->fetch_assoc();
            }
        ?>

            <div class="w3-row-padding">
                <div class="w3-half w3-padding-48">
                    <div class="w3-card-8 w3-white w3-center w3-round-xlarge" style="padding-top:20%;padding-bottom:30%">
                        <p class="w3-text-grey">Français</p>
                        <p class="w3-xxxlarge"><?= $result["fr"] ?></p>
                    </div>
                </div>
                <div class="w3-half w3-padding-48">
                    <div class="w3-card-8 w3-white w3-center w3-round-xlarge" style="padding-top:20%;padding-bottom:30%">
                        <p class="w3-text-grey">Anglais</p>
                        <p class="w3-xxxlarge"><?= $result["eng"] ?></p>
                    </div>
                </div>
            </div>        
            
            <div class="w3-center">
                <a class="w3-btn w3-center w3-xlarge w3-round-large w3-red" href="index.php">Nouveau couple</a>
            </div>
        </section>

        <script>
            function w3_react()
            {
                var navigation = document.getElementsByClassName("w3-sidenav")[0];

                if (navigation.style.display == "block")
                {
                    navigation.style.display = "none";
                }
                else
                {
                    navigation.style.display = "block";
                }
            }
        </script>
    </body>
</html>
