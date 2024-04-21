<?php
    # Vérifier si l'utilisateur est connecté
    session_start();
    require_once 'connexion_bd.php';
    $est_connecte = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
	 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	 <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
	 <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao+Looped:wght@100..900&family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
	 <link href="https://fonts.googleapis.com/css2?family=Reem+Kufi+Fun:wght@400..700&display=swap" rel="stylesheet">	 

</head>
<body>
    <!-- Ajout du titre de site en haut de la page -->
    <div id="entete">
    <a href="index.php" style="text-decoration: none;">
        <h1 class="oswald-font"><span class="text-stroke" style="color : black;">PRIX NOBEL</span></h1>
    </a>
	</div>

    <!-- Ajout du menu avec bootstrap -->
    <div class="menu" style="width: 100%;">
    <nav class="navbar navbar-expand-lg navbar-light justify-content-start px-0">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav w-100 justify-content-between">
                    <li class="nav-item">
                        <a class="nav-link mx-5" id="accueil" href="index.php" style="color: black;">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-5" id="recherche" href="recherche.php" style="color: black;">Recherche</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-5" id="graphique" href="graphique.php" style="color: black;">Graphique</a>
                    </li>
                    <li class="nav-item">
                        <?php
                        if ($est_connecte) {
                            echo '<a class="nav-link mx-5" id="connexion" href="connexion.php" style="color: black;">Connecté <span class="status-indicator connected"></span></a>';
                        } else {
                            echo '<a class="nav-link mx-5" id="connexion" href="connexion.php" style="color: black;">Connexion <span class="status-indicator disconnected"></span></a>';
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    </div>
    <div class="boite_index">
        <div class="contenu">
            <div class="slider-container">
                <button class="prev">&#10094;</button>
                <!-- Affichage des images d'article dans la base de données articles.sql -->
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
                        $index = 1;
                        while ($row = $result->fetch_assoc()) {
                            # Affichage du titre de l'article associé
                            echo '<div class="image-title" style="text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.2);font-weight: bold; font-size: 30px; text-align:center;cursor:default;margin-top:-10px;margin-bottom:10px;">' . $row["titre"] . '</div>';
									 echo '<a href="article' . $index . '.php"><img class="slide" src="' . $row["image"] . '" style="margin: 0 auto;box-shadow: 0 0 5px rgba(1, 1, 1, 0.4); width: 100%; height: 100%; display: none;"></a>';

                            $index++;
                        }
                    } else {
                        echo "0 résultats";
                    }
                    $conn->close();
                    ?>
                </div>
                <!-- Défilement des images grâce aux flèches -->
                <div class="image-selector">
                    <?php
                    for ($i = 0; $i < $result->num_rows; $i++) {
                        echo '<div class="selector-dot" data-index="' . $i . '"></div>';
                    }
                    ?>
                </div>
                <button class="next">&#10095;</button>
            </div>
                <!-- Affichage de l'image pour contacter l'utilisateur si il est connecté -->
                <?php
                if ($est_connecte){
                    echo '<p style="text-align:center;font-size:20px;font-weight:bold;padding-top:20px;">Pour nous contacter : <a href="contacter.php"><img src="Images/icone_commentaires.png" alt="Pour nous contacter" width="30" height="30" /></a></p>';
                }   
                ?>
        </div>   
    </div>
    
    <!-- Fonctionnement des flèches -->
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

    <!-- Agrandissement de l'image d'article quand l'utilisateur passe sa souris dessus -->
    <script>
        $(document).ready(function() {
            $(".slide").hover(function() {
                $(this).css({"transform": "scale(1.01)", "transition": "transform 0.3s"}); 
            }, function() {
                $(this).css({"transform": "scale(1)", "transition": "transform 0.3s"}); 
            });
        });
    </script>

    <script src="script_index.js"></script>
</body>
</html>
