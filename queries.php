<!-- mes requetes sql -->
<?php
include("connexion_base_donnee.php");

// Requête SQL pour récupérer le nombre de lauréats par pays de naissance(nationalité des prix nobel)
$query = "SELECT `Born_country`, COUNT(*) AS nbLauréats
          FROM nomine
          GROUP BY `Born_country`
          HAVING COUNT(*) >= 10
          ORDER BY COUNT(*) DESC";

// Exécution de la requête
$result = $mysqli->query($query);

// Création d'un tableau pour stocker les données
$data = array();

// Récupération des données dans le tableau
while ($row = $result->fetch_assoc()) {
    $data[$row['Born_country']] = $row['nbLauréats'];
}



// Récupération des années 
$queryYears = "SELECT DISTINCT Année FROM prix_nobel   ORDER BY Année ASC" ;
$resultYears = $mysqli->query($queryYears);
$years = array();

while ($row = $resultYears->fetch_assoc()) {
    $years[] = $row['Année'];
}



// ==========================================
// ====Implication des organisations(Axe de X: organisation)
$organisation= "SELECT nom_organisation AS Organisation, COUNT(*) AS NombreLauréats
FROM prix_nobel
JOIN organisation ON prix_nobel.id_organisation = organisation.id_organisation
GROUP BY nom_organisation
HAVING COUNT(*) >= 10
ORDER BY COUNT(*) DESC";
$requeteOrganisation =  $mysqli->query($organisation);
$var_Array_Organisation=array();
while ($row=$requeteOrganisation->fetch_assoc()){
    $var_Array_Organisation[]=$row;
}

// =========================================================
// ===Implications des pays des organisations qui on remporté plus de 5 prix nobel: pays d'origine 
$paysOrganisations = "SELECT pays_organisation AS Pays, COUNT(*) AS NombreLaureats
                FROM organisation
                JOIN prix_nobel ON prix_nobel.id_organisation = organisation.id_organisation
                GROUP BY pays_organisation
                HAVING COUNT(*) >= 5
                ORDER BY COUNT(*) DESC";

$requetePaysOrganisations = $mysqli->query($paysOrganisations);
$var_Array_PaysOrg = array();

while ($row = $requetePaysOrganisations->fetch_assoc()) {
    $var_Array_PaysOrg[] = $row;
}
$paysOrganisationsJSON = json_encode($var_Array_PaysOrg);


// =========================================================
// ===nombre de lauréats et evolution au fil des années dans différentes catégories(historique) tous les 20 ans
$queryHistorique = "SELECT 
CONCAT(FLOOR(p.Année / 20) * 20, ' - ', FLOOR(p.Année / 20) * 20 + 19) AS Intervalle,
c.Nom_catégorie,
COUNT(*) AS nbLauréats
FROM 
prix_nobel p
JOIN 
nomine n ON p.id_nominé = n.`Id_nominé`
JOIN 
categorie c ON p.id_category = c.Id_catégorie
GROUP BY 
FLOOR(p.Année / 20),
c.Nom_catégorie
ORDER BY 
FLOOR(p.Année / 20),
c.Nom_catégorie;
";

$requete = $mysqli->query($queryHistorique);

$donnees = array();

while ($row = $requete->fetch_assoc()) {
    $donnees[] = $row;
}
$donneesJSON_Historique = json_encode($donnees);

//-------------------------------------------------------------------------------------

///========================================= nv query 
$queryCam = "SELECT Gender, COUNT(*) AS nombre_prix_nobel
          FROM prix_nobel pn
          JOIN nomine n ON pn.id_nominé = n.`Id_nominé`
          WHERE Gender IN ('female', 'male')
          GROUP BY Gender";

$queryCamExecute = $mysqli->query($queryCam);
$queryCamArray = array();
while ($row = $queryCamExecute->fetch_assoc()){
    $queryCamArray[] = $row;
}

// récupération des années par decennies
$queryDecennies = "SELECT DISTINCT CONCAT(FLOOR(Année / 10) * 10, ' - ', FLOOR(Année / 10) * 10 + 9) AS DecennieStart FROM prix_nobel ORDER BY DecennieStart ASC";
$resultDecennies = $mysqli->query($queryDecennies);
$decennies = array(); // le tableau il est vide 

while ($row = $resultDecennies->fetch_assoc()) {
    $decennies[] = $row['DecennieStart'];
}



//=============================================================
// requete pour la visualisation des impacts des prix nobels par pays et Catégorie 
$impactQuery = "SELECT DISTINCT
Nom_catégorie, 
`Born_Country` AS Pays, 
Gender, 
COUNT(*) AS TotalLaureats
FROM 
nomine
JOIN 
prix_nobel ON nomine.`Id_nominé` = prix_nobel.id_nominé
JOIN 
categorie ON prix_nobel.id_category = categorie.Id_catégorie
GROUP BY 
Nom_catégorie, Pays, Gender
ORDER BY 
Nom_catégorie, Pays;
";

$resultImpact = $mysqli->query($impactQuery);
$impactData = [];

while ($row = $resultImpact->fetch_assoc()) {
    $impactData[] = $row;
}

$jsonImpactData = json_encode($impactData);


//=============================================================
// récupération du nombre de prix Nobel pour chaque catégorie
$queryCatCount = "SELECT 
    p.Année, 
    c.Nom_catégorie, 
    n.Gender, 
    COUNT(p.id_nominé) AS nbPrixNobel
FROM 
    prix_nobel p
JOIN 
    nomine n ON p.id_nominé = n.`Id_nominé`
JOIN 
    categorie c ON c.Id_catégorie = p.id_category
GROUP BY 
    p.Année, 
    c.Nom_catégorie, 
    n.Gender
ORDER BY 
    p.Année ASC, 
    c.Nom_catégorie ASC;";


$resultCatCount = $mysqli->query($queryCatCount);
$catCountData = array();

while ($row = $resultCatCount->fetch_assoc()) {
    $catCountData[] = $row;
}

//conversion des donnees pour qu'elles soient facilement utilissables dans le graphes 
$categories = [];
$maleData= [];
$femaleData = [];
$datasets = [];

foreach($catCountData as $data ){
    $annne = $data['Année'];
    $categorie = $data['Nom_catégorie'];
    $nbPrixNobel = $data['nbPrixNobel'];
    $gender = $data['Gender'];

    if ($gender == 'male') {
        $maleData[$annne][$categorie] = (isset($maleData[$annne][$categorie]) ? $maleData[$annne][$categorie] : 0) + $nbPrixNobel;
    } else if ($gender == 'female') {
        $femaleData[$annne][$categorie] = (isset($femaleData[$annne][$categorie]) ? $femaleData[$annne][$categorie] : 0) + $nbPrixNobel;
    }

    if (!in_array($categorie, $categories)){
        $categories[] = $categorie;
    }
    if(!isset($datasets[$annne])){
        $datasets[$annne] = [
            'label' => $annne,
            'data' => array_fill(0, count($categories), 0),
          
        ];
    }
    $index = array_search($categorie, $categories);
    $datasets[$annne]['data'][$index] = $nbPrixNobel;
}
$datasets = array_values($datasets);
$jsonCategories = json_encode($categories);
$jsonMaleData = json_encode($maleData);
$jsonFemaleData = json_encode($femaleData);
$jsonDatasets = json_encode($datasets);
//=========================================================================================

// données de la table categorie
$queryCat = "SELECT Nom_catégorie FROM categorie";
$resultCat = $mysqli->query($queryCat);
$catData = array();
while ($row = $resultCat->fetch_assoc()) {
    $catData[] = $row;
}

// données de catégorie en format JSON
$jsonCatData = json_encode($catData);



// recuperation des Sexes 
$maleGenderQuery = "SELECT * FROM  nomine WHERE Gender='male'";
$femaleGenderQuery = "SELECT * FROM nomine WHERE Gender='female'";
//male query fetching from the database
$maleGenderQueryRes = $mysqli->query($maleGenderQuery);
$maleGenderQueryResArr = array();
while ($row = $maleGenderQueryRes->fetch_assoc()) {
    $maleGenderQueryResArr[] = $row;
}
//female query fetching from the database 
$femaleGenderQueryRes = $mysqli->query($femaleGenderQuery);
$femaleGenderQueryArr = array();
while ($row = $femaleGenderQueryRes->fetch_assoc()){
    $femaleGenderQueryArr[] = $row;
}

// recuperation des pays : pays de naissance et pays de décés
$birthCountry ="SELECT   DISTINCT `Born_Country` FROM nomine"; 
$deathCountry = "SELECT  DISTINCT `Died_country` FROM nomine";

//  requete pour recuperation des pays de naissance à partir de la base de donnée
$birthCountryQuery = $mysqli->query($birthCountry);
$birthCountryArrResponseQuery = array();
while ($row = $birthCountryQuery->fetch_assoc()){
    $birthCountryArrResponseQuery[] = $row;
}

//  requete pour recuperation des pays de décés à partir de la base de donnée
$deathCountryQuery = $mysqli->query($deathCountry);
$deathCountryArrResponseQuery = array();
while ($row = $deathCountryQuery->fetch_assoc()){
    $deathCountryArrResponseQuery[] = $row;
}

?>

<?php
    session_start();
    require_once 'connexion_bd.php';
    $est_connecte = isset($_SESSION['user_id']);
?>