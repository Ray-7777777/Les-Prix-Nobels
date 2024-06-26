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
    <link rel="preconnect" href="https://fonts.googleapis.com">
	 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	 <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
	 <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao+Looped:wght@100..900&family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
	 <link href="https://fonts.googleapis.com/css2?family=Reem+Kufi+Fun:wght@400..700&display=swap" rel="stylesheet">	 

</head>

<body>
<script>
    $(document).ready(function() {
    $('#loginForm').submit(function(e) {
        e.preventDefault();

        var email = $('#emailInput').val();
        var password = $('#passwordInput').val();

        // Afficher les valeurs pour débogage
        console.log('Email:', email);
        console.log('Password:', password);

        // Envoyer la requête AJAX vers le script de traitement PHP
        $.ajax({
            type: 'POST',
            url: 'traitement.php',
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                console.log('Response:', response); // Afficher la réponse du serveur
                if (response === 'success') {
                    window.location.href = 'index.php';
                } else {   
                $('#message').html('<div style="color: red;">Erreur de connexion. Veuillez réessayer.</div>');
                }
            }
        });
    });
});


</script>
    <div id="entete">
    <a href="index.php" style="text-decoration: none;">
        <h1 class="oswald-font"><span class="text-stroke" style="color : black;">PRIX NOBEL</span></h1>
    </a>
	</div>

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
    <div class="boite_connexion">
        <div class="contenu_connexion">
            <?php
            if ($est_connecte) {
                echo "<div id='titre-co'>Connexion</div>";
                echo "<div id='deja-connecte'>Vous êtes déjà connecté.</div>";
                echo "<div id='se-deconnecter'><a href='deconnexion.php'>Se déconnecter</a></div>";
            } else {
            ?>
                <form id="loginForm" class="text-center">
                    <h2 class="titre-connexion">Formulaire de Connexion</h2>
                    <div id="formu">
                        <div id="email" class="form-group row justify-content-center">
                            <label for="emailInput" class="form-group col-form-label">Email :</label>
                            <div class="col-auto">
                                <input type="email" id="emailInput" name="email" class="form-control form-control-sm custom-input" required autocomplete="email">
                            </div>
                        </div>
                        <div id="motdepasse"class="form-group row justify-content-center">
                            <label for="passwordInput" class="form-group col-form-label">Mot de passe :</label>
                            <div  class="col-auto">
                                <input type="password" id="passwordInput" name="password" class="form-control form-control-sm custom-input" required autocomplete="password">
                            </div>
                        </div>
                        <div class="text-center">
                            <input id="bouton-co" type="submit" value="Se Connecter" class="btn btn-primary mt-3" style="background-color: #FFFCE9;border:solid black 1px;color:black;font-weight:bold;text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.4);box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.4);">
                        </div>
                    </div>
                </form>
                <?php
                    if (!$est_connecte) {
                        echo '<a id="inscription" href="inscription.php" style="display: block; text-align: center; color: black; text-decoration: underline; padding-top: 10px;">Inscription</a>';
                    }
                ?>
            <?php } ?>
            </div>
    <div id="message" style="text-align:center">
    </div>
        </div>
    
    
    
    
</body>
</html>
