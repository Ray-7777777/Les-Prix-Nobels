<?php
session_start();
session_destroy();
echo "<div>Vous venez de vous déconnecter. Vous serez redirigé vers la page d'accueil dans 5 secondes.</div>";
header("refresh:5;url=index.php");
exit();
?>
