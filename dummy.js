function displayMaleData() {
    var selectElement = document.createElement('select');
    selectElement.setAttribute('id', 'maleSelect');



    <?php 
    foreach ($maleGenderQueryResArr as $item) {
        $prenomNom = addslashes($item['Prénom'] . ' ' . $item['Nom']);
        echo "var option = document.createElement('option');";
        echo "option.value = \"$prenomNom\";";
        echo "option.textContent = \"$prenomNom\";";
        echo "selectElement.appendChild(option);";
    }
    ?>

    return selectElement;
}
document.getElementById('SelectedMaleGender').addEventListener('click', function() {
    var selectElement = displayMaleData();
    document.body.appendChild(selectElement);
});



function displayFemaleData(){
    var selectedElement = document.createElement('select');
    selectedElement.setAttribute('id', 'maleSelect');

    <?php 
    foreach ($femaleGenderQueryArr as $item) {
        $prenomNom = addslashes($item['Prénom'] . ' ' . $item['Nom']);
        echo "var option = document.createElement('option');";
        echo "option.value = \"$prenomNom\";";
        echo "option.textContent = \"$prenomNom\";";
        echo "selectedElement.appendChild(option);";
    }
    ?>

    return selectedElement;
}
document.getElementById('SelectedFemaleGender').addEventListener('click', function(){
    var selectElement = displayFemaleData();
    document.body.appendChild(selectElement);
})


// display Born Country  without duplicated value in the query
function displayBornCountry(){
    var selectedElement = document.createElement('select');
    selectedElement.setAttribute('id', 'bornCountrySelect');

    <?php 
    foreach ($birthCountryArrResponseQuery as $item) {
        $country = addslashes($item['Born country']);
        echo "var option = document.createElement('option');";
        echo "option.value = \"$country\";";
        echo "option.textContent = \"$country\";";
        echo "selectedElement.appendChild(option);";
    }
    ?>

    return selectedElement;
}

document.getElementById('SelectedBornCountry').addEventListener('click', function(){
    var selectElement = displayBornCountry();
    document.body.appendChild(selectElement);
})

// displaying Died Country without duplicated Value in the query 

function displayDiedCountry(){
    var selectedElement = document.createElement('select');
    selectedElement.setAttribute('id', 'deathCountrySelect');

<?php 
foreach ($deathCountryArrResponseQuery as $item) {
    $country = addslashes($item['Died country']);
    echo "var option = document.createElement('option');";
    echo "option.value = \"$country\";";
    echo "option.textContent = \"$country\";";
    echo "selectedElement.appendChild(option);";
}
?>

return selectedElement;

}

document.getElementById('SelectedDiedCountry').addEventListener('click', function(){
    var selectElement = displayDiedCountry();
    document.body.appendChild(selectElement);
})






//dead code 





// recuperearion des decennies , categories et nobre de prix nobels 
SELECT 
  CONCAT(FLOOR(Année / 10) * 10, ' - ', FLOOR(Année / 10) * 10 + 9)  AS Decennie,
  c.Nom_catégorie,
  COUNT(*) AS nbPrixNobel
FROM 
  prix_nobel p
JOIN 
  categorie c ON p.id_category = c.Id_catégorie
GROUP BY 
  Decennie,
  c.Nom_catégorie
ORDER BY 
  Decennie ASC, 
  c.Nom_catégorie ASC;







  // changing the doisplay by deleting some item 
  <!-- Année -->
  <!-- <div class="dropdown"> 
      <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Année
      </a>
      <select id="selectYear">
          <?php
          foreach ($years as $year) {
              echo "<option value=\"$year\">$year</option>";
          }
          ?>
      </select>
  </div>   </div><!-- Année -->
  <div class="dropdown">
      <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Année
      </a>
      <select id="selectYear">
          <?php
          foreach ($years as $year) {
              echo "<option value=\"$year\">$year</option>";
          }
          ?>
      </select>
  </div> 


  <!-- <div class="decennies"> -->
  <ul>
      <?php
      foreach ($decennies as $decennieStart) {
          echo "<li>$decennieStart</li>"; 
      }
      ?>
  </ul>
</div>
<!-- fin div année -->
<!-- Categorie -->
<!-- <div class="dropdown"> 
  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Catégorie
  </a>
  <select id="selectCategorie">
      <?php
      foreach ($catData as $cat) {
          echo "<option value=\"" . $cat['Nom_catégorie'] . "\">" . $cat['Nom_catégorie'] . "</option>";
        
      }
      ?>
  </select>
</div>
<!-- fin div catégorie -->

<!-- <div class="dropdown"> -->
  <a class="btn btn-secondary dropdown-toggle" href="#" id="Country" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Pays
  </a>
  <!--<div class="dropdown-menu">
      <a class="dropdown-item" href="#" id="SelectedBornCountry" onclick="displayBornCountry()">de naissance</a>
      <a class="dropdown-item" href="#" id="SelectedDiedCountry" onclick="displayDiedCountry()">de décés</a>
  </div> -->
</div>
<!-- <div class="decennies"> -->
  <ul>
      <?php
      foreach ($decennies as $decennieStart) {
          echo "<li>$decennieStart</li>"; 
      }
      ?>
  </ul>
</div>
<!-- fin div année -->
<!-- Categorie -->
<!-- <div class="dropdown"> -->
  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Catégorie
  </a>
  <select id="selectCategorie">
      <?php
      foreach ($catData as $cat) {
          echo "<option value=\"" . $cat['Nom_catégorie'] . "\">" . $cat['Nom_catégorie'] . "</option>";
        
      }
      ?>
  </select>
</div>
<!-- fin div catégorie -->

<!-- <div class="dropdown"> -->
  <a class="btn btn-secondary dropdown-toggle" href="#" id="Country" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Pays
  </a>
  <!--<div class="dropdown-menu">
      <a class="dropdown-item" href="#" id="SelectedBornCountry" onclick="displayBornCountry()">de naissance</a>
      <a class="dropdown-item" href="#" id="SelectedDiedCountry" onclick="displayDiedCountry()">de décés</a>
  </div> -->
</div>
<!-- fin div catégorie -->
<!-- <div class="dropdown"> -->
  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Nombre de prix nobel
  </a>
  <div class="dropdown-menu">
      <a class="dropdown-item" href="#">1</a>
      <a class="dropdown-item" href="#">2</a>
      <a class="dropdown-item" href="#">3</a>
      <a class="dropdown-item" href="#">4</a>
      <a class="dropdown-item" href="#">5</a>

  </div>
</div>