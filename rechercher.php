<?php
    //Connection à la base de données
    require_once "connexion_bd.php";
    $bdd = getBD();

    //Si la barre de recherche n'est pas vide
    if(isset($_POST["recherche"]) && $_POST["recherche"] != ""){
        $mot = "";
        $url = "";
        $titre = "";
        $chaine = "";
        $compteur = "";
        //Mise en forme de la recherche en enlevant les majuscules et les mots parasites définis
        $mots_cles = strtolower(mb_convert_encoding($_POST["recherche"], 'UTF-8', 'ISO-8859-1'));
        $mots_parasites = "a|abord|absolument|afin|ah|ai|aie|aient|aies|ailleurs|ainsi|ait|allaient|allo|allons|allô|alors|anterieur|anterieure|anterieures|apres|après|as|assez|attendu|au|aucun|aucune|aucuns|aujourd|aujourd'hui|aupres|auquel|aura|aurai|auraient|aurais|aurait|auras|aurez|auriez|aurions|aurons|auront|aussi|autant|autre|autrefois|autrement|autres|autrui|aux|auxquelles|auxquels|avaient|avais|avait|avant|avec|avez|aviez|avions|avoir|avons|ayant|ayez|ayons|b|bah|bas|basee|bat|beau|beaucoup|bien|bigre|bon|boum|bravo|brrr|c|car|ce|ceci|cela|celle|celle-ci|celle-là|celles|celles-ci|celles-là|celui|celui-ci|celui-là|celà|cent|cependant|certain|certaine|certaines|certains|certes|ces|cet|cette|ceux|ceux-ci|ceux-là|chacun|chacune|chaque|cher|chers|chez|chiche|chut|chère|chères|ci|cinq|cinquantaine|cinquante|cinquantième|cinquième|clac|clic|combien|comme|comment|comparable|comparables|compris|concernant|contre|couic|crac|d|da|dans|de|debout|dedans|dehors|deja|delà|depuis|dernier|derniere|derriere|derrière|des|desormais|desquelles|desquels|dessous|dessus|deux|deuxième|deuxièmement|devant|devers|devra|devrait|different|differentes|differents|différent|différente|différentes|différents|dire|directe|directement|dit|dite|dits|divers|diverse|diverses|dix|dix-huit|dix-neuf|dix-sept|dixième|doit|doivent|donc|dont|dos|douze|douzième|dring|droite|du|duquel|durant|dès|début|désormais|e|effet|egale|egalement|egales|eh|elle|elle-même|elles|elles-mêmes|en|encore|enfin|entre|envers|environ|es|essai|est|et|etant|etc|etre|eu|eue|eues|euh|eurent|eus|eusse|eussent|eusses|eussiez|eussions|eut|eux|eux-mêmes|exactement|excepté|extenso|exterieur|eûmes|eût|eûtes|f|fais|faisaient|faisant|fait|faites|façon|feront|fi|flac|floc|fois|font|force|furent|fus|fusse|fussent|fusses|fussiez|fussions|fut|fûmes|fût|fûtes|g|gens|h|ha|haut|hein|hem|hep|hi|ho|holà|hop|hormis|hors|hou|houp|hue|hui|huit|huitième|hum|hurrah|hé|hélas|i|ici|il|ils|importe|j|je|jusqu|jusque|juste|k|l|la|laisser|laquelle|las|le|lequel|les|lesquelles|lesquels|leur|leurs|longtemps|lors|lorsque|lui|lui-meme|lui-même|là|lès|m|ma|maint|maintenant|mais|malgre|malgré|maximale|me|meme|memes|merci|mes|mien|mienne|miennes|miens|mille|mince|mine|minimale|moi|moi-meme|moi-même|moindres|moins|mon|mot|moyennant|multiple|multiples|même|mêmes|n|na|naturel|naturelle|naturelles|ne|neanmoins|necessaire|necessairement|neuf|neuvième|ni|nombreuses|nombreux|nommés|non|nos|notamment|notre|nous|nous-mêmes|nouveau|nouveaux|nul|néanmoins|nôtre|nôtres|o|oh|ohé|ollé|olé|on|ont|onze|onzième|ore|ou|ouf|ouias|oust|ouste|outre|ouvert|ouverte|ouverts|o||où|p|paf|pan|par|parce|parfois|parle|parlent|parler|parmi|parole|parseme|partant|particulier|particulière|particulièrement|pas|passé|pendant|pense|permet|personne|personnes|peu|peut|peuvent|peux|pff|pfft|pfut|pif|pire|pièce|plein|plouf|plupart|plus|plusieurs|plutôt|possessif|possessifs|possible|possibles|pouah|pour|pourquoi|pourrais|pourrait|pouvait|prealable|precisement|premier|première|premièrement|pres|probable|probante|procedant|proche|près|psitt|pu|puis|puisque|pur|pure|q|qu|quand|quant|quant-à-soi|quanta|quarante|quatorze|quatre|quatre-vingt|quatrième|quatrièmement|que|quel|quelconque|quelle|quelles|quelqu'un|quelque|quelques|quels|qui|quiconque|quinze|quoi|quoique|r|rare|rarement|rares|relative|relativement|remarquable|rend|rendre|restant|reste|restent|restrictif|retour|revoici|revoilà|rien|s|sa|sacrebleu|sait|sans|sapristi|sauf|se|sein|seize|selon|semblable|semblaient|semble|semblent|sent|sept|septième|sera|serai|seraient|serais|serait|seras|serez|seriez|serions|serons|seront|ses|seul|seule|seulement|si|sien|sienne|siennes|siens|sinon|six|sixième|soi|soi-même|soient|sois|soit|soixante|sommes|son|sont|sous|souvent|soyez|soyons|specifique|specifiques|speculatif|stop|strictement|subtiles|suffisant|suffisante|suffit|suis|suit|suivant|suivante|suivantes|suivants|suivre|sujet|superpose|sur|surtout|t|ta|tac|tandis|tant|tardive|te|tel|telle|tellement|telles|tels|tenant|tend|tenir|tente|tes|tic|tien|tienne|tiennes|tiens|toc|toi|toi-même|ton|touchant|toujours|tous|tout|toute|toutefois|toutes|treize|trente|tres|trois|troisième|troisièmement|trop|très|tsoin|tsouin|tu|té|u|un|une|unes|uniformement|unique|uniques|uns|v|va|vais|valeur|vas|vers|via|vif|vifs|vingt|vivat|vive|vives|vlan|voici|voie|voient|voilà|voire|vont|vos|votre|vous|vous-mêmes|vu|vé|vôtre|vôtres|w|x|y|z|zut|à|â|ça|ès|étaient|étais|était|étant|état|étiez|étions|été|étée|étées|étés|êtes|être|ô";
        $mots_exclus= explode("|", $mots_parasites);
        //Déclaration du début de la requête
        $requete_and = "SELECT Id_nominé, Prénom, Nom, biographie FROM nomine ";
        //Récupération des filtres s'il y en a
        if(isset($_POST["filtres"])) {
            $filtres = $_POST["filtres"];
        } else {
            $filtres = array();
        }

        $compteur = 0;
        $compteur_or = 0;
        //Ajout des filtres à la requête
        foreach ($filtres as $category => $values) {
            foreach ($values as $value) {
                if($compteur == 0){
                    $requete_and .= "WHERE ";
                    $compteur = 1;
                } else {
                    $requete_and .= " AND ";
                }
                $requete_and .= $category . "='" . $value . "'";
            }
            $requete_or = $requete_and;
            $requete_or .= "AND (";
            $compteur_or = 1;
        }
        if($filtres == array()){
            $requete_or = $requete_and;
        }
        
        //On enlève les mots parasites des mots saisis en barre de recherche
        foreach ($mots_exclus as $mot_exclu) {
            $pattern = '/\b' . preg_quote($mot_exclu, '/') . '\b/i';
            $mots_cles = preg_replace($pattern, '', $mots_cles);
        }
        
        $mots_cles_fin = explode(" ",$mots_cles);
        
        //On enlève les "s" aux mots clés et on les ajoute aux requêtes
        foreach ($mots_cles_fin as $mot) {
            $mot = rtrim($mot, "s");
            if(strlen($mot) > 3){
                if($compteur == 0){
                    $requete_or .= "WHERE mots_cles LIKE '%" . $mot . "%' ";
                    $requete_and .= "WHERE mots_cles LIKE '%" . $mot . "%' ";
                } else {
                    $requete_and .= "AND mots_cles LIKE '%" . $mot . "%' ";
                    if ($compteur_or == 1){
                        $requete_or .= "mots_cles LIKE '%" . $mot . "%' ";
                        $compteur_or = 2;
                    }
                    else{
                        $requete_or .= "OR mots_cles LIKE '%" . $mot . "%' ";
                    }
                }
                $compteur++;
            }
        }

        if($compteur_or == 2){
            $requete_or .= ")";
        }

        $retour = $bdd->prepare($requete_and);
        $retour->execute();

        $resultat = $retour->fetchAll(PDO::FETCH_ASSOC);

        //Si la requête où l'on cherche à ce que tous les mots clés soient dans l'article ne retourne rien, on effectue la recherche avec minimum un mot clé présent dans l'article
        if(sizeof($resultat)==0){
            $retour = $bdd->prepare($requete_or);
            $retour->execute();

            $resultat = $retour->fetchAll(PDO::FETCH_ASSOC);
        }

        //Un titre (nom et prénom d'un nominé) comptera 10 fois plus qu'un mot présent dans l'article
        $poidsTitre = 10;
        $poidsBiographie = 1;

        foreach ($resultat as &$row) {
            $score = 0;
        
            //On calcule les scores pour le titre de chaque article trouvé 
            foreach ($mots_cles_fin as $mot) {
                $mot = rtrim($mot, "s");
                if (stripos($row['Prénom'] . ' ' . $row['Nom'], $mot) !== false) {
                    $score += $poidsTitre;
                }
            }
            
            //On calcule les scores pour le contenu de chaque article trouvé 
            foreach ($mots_cles_fin as $mot) {
                $mot = rtrim($mot, "s");
                if (stripos($row['biographie'], $mot) !== false) {
                    $score += $poidsBiographie;
                }
            }
        
            $row['score'] = $score;
        }
        
        //Fonction qui compare les scores de deux articles
        function comparerScore($a, $b) {
            return $b['score'] - $a['score'];
        }
        
        //Classement des articles par ordre décroissant de score
        usort($resultat, 'comparerScore');
        //Limitation du nombre de résultats à 25
        $resultat = array_slice($resultat, 0, 25);

        //Déconnection de la base de données
        $bdd = null;

        echo json_encode($resultat);

    }
?>