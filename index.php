<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="style.css?v=1.1" type="text/css" media="screen" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div id="entete">
        <h1>PRIX NOBEL</h1>
        <ol class="menu" id="menu">
            <li><a id="accueil" href="index.php">Accueil</a></li>
            <li><a id="recherche" href="recherche.php">Recherche</a></li>
            <li><a id="graphique" href="./real.php">Graphique 1</a></li>
            <li><a id="graphique" href="./page_graphique copy.php">Graphique 2</a></li>
            <li><a id="connexion" href="connexion.php">Connexion</a></li>

        </ol>
    </div>
    <div class="boite_index">
        <div class="contenu_index">
            <div class="slider-container">
                <button class="prev">&#10094;</button>
                <div id="images-container">
                    <?php
                 
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "articles";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT DISTINCT image, titre FROM article";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="image-title">' . $row["titre"] . '</div>';
                            echo '<a href="article1.php"><img class="slide" src="' . $row["image"] . '" style="margin-left:-1.4px;border: solid 0.1rem black; width: 100%; height: 100%; display: none;"></a>';
                        }
                    } else {
                        echo "0 résultats";
                    }
                    $conn->close();
                    ?>
                </div>
                <div class="image-selector">
                  
                    <?php
                    for ($i = 0; $i < $result->num_rows; $i++) {
                        echo '<div class="selector-dot" data-index="' . $i . '"></div>';
                    }
                    ?>
                </div>
                <button class="next">&#10095;</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let currentImage = 0;
            const totalImages = $(".slide").length;

            updateImage();
            updateSelectorDots();

            $(".next").click(function() {
                currentImage = (currentImage + 1) % totalImages;
                updateImage();
                updateSelectorDots();
            });

            $(".prev").click(function() {
                currentImage = (currentImage - 1 + totalImages) % totalImages;
                updateImage();
                updateSelectorDots();
            });

            $(".selector-dot").click(function() {
                currentImage = $(this).data("index");
                updateImage();
                updateSelectorDots();
            });

            function updateImage() {
                $(".slide").hide();
                $(".slide").eq(currentImage).show();
                $(".image-title").hide(); 
                $(".image-title").eq(currentImage).show(); 
            }

            function updateSelectorDots() {
                $(".selector-dot").css("background-color", "white"); 
                $(".selector-dot").eq(currentImage).css("background-color", "black"); 
            }
        });
    </script>
    <footer id="footer_commentaires">
    	<a href="commentaires.php" style="color: black">Page des commentaires</a>
    	<img src="Images/icone_commentaires.png" style="vertical-align: middle; margin-left: 5px; width: 20px; height: 20px;">
    </footer>
</body>

</html>
