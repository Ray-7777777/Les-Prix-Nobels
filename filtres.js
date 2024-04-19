//Fonction gérant la sélection d'une variable à filtrer et affichant son bouton correspondant qui contient un menu déroulant avec toutes ses valeurs possibles
$(".dropdown-item.BFiltre").click(function(e){
    e.preventDefault();
    var $this = $(this);
    var buttonText = $(this).text();
    var name = $(this).attr("name");
    //Appel de filtres.php afin de récupérer dans la base de données toutes les valeurs possibles du filtre en question
    $.ajax({
        type: "POST",
        url: "filtres.php",
        data: {
            id: $(this).attr('id')
        },
        success: function(result) {
            filtres= $(".filters");
            var items = JSON.parse(result);
            //Création du bouton et du menu déroulant 
            var text = "<div class='dropdown' id='sousFiltre'> \n <a class='btn btn-secondary dropdown-toggle' href='#' role='button' id='dropdownMenuLink' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+ buttonText +"</a> \n <div class='dropdown-menu sousFiltre' data-category='"+name+"'> ";
            //Ajout des valeurs de la réponse au menu déroulant
            items.forEach(function(element) {
                text += "<button class='dropdown-item SFiltre' type='button' id='Filtre"+element[name]+"'>"+element[name]+"</button>\n";
            });
            //Désactivation du bouton correspondant afin qu'on ne puisse pas le cliquer quand le filtre est déja selectionné
            $this.prop("disabled", "true");
            text += "</div> </div>";
            filtres.append(text);
        },
        error: function(xhr, status, error) {
            console.log("Erreur lors de la requête AJAX: " + status + " - " + error);
        }
    })

})

//Fonction gérant le click sur un filtre d'un menu déroulant
$(".filters").on("click", ".dropdown-item.SFiltre", function(e){
    e.preventDefault();
    //Création d'une div avec le nom du filtre et une croix clickable pour le déselectionner et ajout à la page de recherche
    var text = "<div class='FiltreCroix'><p class='selectedFilter' data-category='"+$(this).parent().data("category")+"' data-name='"+$(this).text()+"'>"+$(this).text()+"</p><span class='float-right clickable close-icon'> </span></div>";
    $("#activeFilters").append(text);
    //Désactivation du filtre dans le menu déroulant afin de ne pas pouvoir le sélectionner une deuxième fois
    $(this).prop("disabled", true);
})

//Gestion du click sur la croix d'un filtre sélectionné
$("#activeFilters").on("click", ".close-icon", function(e){
    var name = $(this).prev().data("name");
    var escapedName = name.replace(/([ #;&,.+*~':"!^$[\]()=>|\/@])/g, "\\$1");
    //Réactivation du bouton dans le menu déroulant
    $("#Filtre"+escapedName).prop("disabled", false);
    $(this).parent().remove();
});