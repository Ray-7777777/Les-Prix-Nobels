<?php
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
</head>
<body>
    <div id="entete">
        <a href="index.php" style="text-decoration: none;">
            <h1><span class="text-stroke" style="color : black;">PRIX NOBEL</span></h1>
        </a>
    </div>
    <div class="menu" style="width: 100%; background-color: white;">
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
                            <a class="nav-link mx-5" id="graphique" href="./page_graphique copy.php" style="color: black;">Graphique</a>
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
                            echo '<div class="image-title">' . $row["titre"] . '</div>';
                            echo '<a href="article' . $index . '.php"><img class="slide" src="' . $row["image"] . '" style="margin-left:-1.4px;border: solid 0.1rem black; width: 100%; height: 100%; display: none;"></a>';
                            $index++;
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
     
 
    <script src="script_index.js"></script>
</body>
</html>
