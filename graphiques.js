//Fonction lorsque l'on clique sur un bouton pour sélectionner une variable
$(document).on("click", ".btnVar", function(e) {
    e.preventDefault();
    var $this = $(this);
    var axe = $this.data("axe");
    var textP = $(this).text().replace(/\+/g, ' ');
    //On crée une div avec l'axe en question la variable sélectionnée ainsi qu'une croix clickable pour le déselectionner
    var text = "<div class='FiltreCroix'><p class='selectedVar" + axe + "' data-axe='" + axe + "' data-category='" + $(this).data("cat") + "' data-name='" + textP + "'>" + axe + ": " + textP + "</p><span class='float-right clickable close-icon'> </span></div>";
    //On ajoute cette div dans l'objet correspondant en fonction de son axe
    if (axe == "X") {
        $("#varActX").empty();
        $("#varActX").append(text);
    } else {
        $("#varActY").empty();
        $("#varActY").append(text);
    }
    //On appelle la fonction updateGraph avec le nombre de variables sélectinnées, ceci est fait pour que les types de graphiques ne nécessitant qu'une variable soient fonctionnels
    if ($("#varActX").html().trim() !== '' && $("#varActY").html().trim() !== ''){
        updateGraph(2);
    }
    else if($("#varActX").html().trim() !== ''){
        updateGraph(1);
    }
})

//Fonction gérant le choix du type de graphique
$(document).on("click", ".typeG", function(e) {
    e.preventDefault();
    var $this = $(this);
    id = $this.attr("id");
    $(".titreVariables").css("display", "block");
    $("#varActX").empty();
    $("#varActY").empty();
    var typeName = "";
    //Suivant le type de graphique selectionné on affiche les boutons de sélection de variables qui sont utilisables
    if (id == "Barre") {
        typeName = "Barre";
        $(".btnVar").css("display", "none");
        $(".btnVar").css("display", "inline-block");
        $("#activeY").css("display", "block");
        $("#titreY").css("display", "block");
        $(".activeGraph").attr("id", "bar");
    } 
    else if (id == "Circulaire") {
        typeName = "Circulaire";
        $(".btnVar").css("display", "none");
        $("#GenderX").css("display", "inline-block");
        $("#CategoryX").css("display", "inline-block");
        $("#NaissanceX").css("display", "inline-block");
        $("#DécèsX").css("display", "inline-block");
        $("#activeY").css("display", "none");
        $("#titreY").css("display", "none");
        $(".activeGraph").attr("id", "pie");
    } 
    else if (id == "Linéaire") {
        typeName = "Linéaire";
        $(".btnVar").css("display", "none");
        $("#AnnéeX").css("display", "inline-block");
        $("#GenderY").css("display", "inline-block");
        $("#NaissanceY").css("display", "inline-block");
        $("#DécèsY").css("display", "inline-block");
        $(".activeGraph").attr("id", "line");
    } 
    else if (id == "BarreEmp") {
        typeName = "BarreEmp";
        $(".btnVar").css("display", "none");
        $("#GenderX").css("display", "inline-block");
        $("#CategoryX").css("display", "inline-block");
        $("#NaissanceX").css("display", "inline-block");
        $("#DécèsX").css("display", "inline-block");
        $("#AnnéeX").css("display", "inline-block");
        $("#NombrePrixY").css("display", "inline-block");
        $(".activeGraph").attr("id", "bar");
    } 
    else if (id == "Nuage") {
        typeName = "Nuage";
        $(".btnVar").css("display", "none");
        $(".btnVar").css("display", "inline-block");
        $(".activeGraph").attr("id", "scatter");
    }
    //On affiche le type de graphique sélectionné
    $("#activeGraph").text("Type de graphe : " + typeName);
    //Si une variable est sélectionnée on appelle updateGraph()
    if ($('.selectedVarX').length > 0){
        updateGraph();
    }
})

//Fonction qui gère le click sur la croix attachée à une variable sélectionnée
$("#activeVar").on("click", ".close-icon", function(e) {
    var axe = $(this).prev().data('axe');
    $('button[data-axe="' + axe + '"]').prop("disabled", false);
    $(this).parent().remove();
});

//Fonction permettant de tracer le graphique
function updateGraph(nb) {
    //Si le nombre de variables sélectionnées est de 2
    if(nb==2){
        var categoryY = $('.selectedVarY').data('category');
        var nameY = $('.selectedVarY').data('name');
        if (nameY !== undefined) {
            //On remplace les caractères inadaptés
            namePY = nameY.replace(/\+/g, ' ');
        }
        else {
            namePY = 'defaultName';
        }
    } //Sinon (s'il est de 1), on comptera juste le nombre de prix en fonction de la variable X
    else{
        namePY = "NombrePrix";
        categoryY = "NombrePrix";
    }
    //Récupération des variables utiles, définition d'autres pour la création du graphique
    var categoryX = $('.selectedVarX').data('category');
    var nameX = $('.selectedVarX').data('name');
    var namePX = nameX.replace(/\+/g, ' ');
    var labels = [];
    var data = [];
    var borderWidth = 1;
    var graphType = $(".activeGraph").attr("id");
    //Appel de graphiques.php avec l'action tracer et les variables qui permettront d'exécuter la requête correspondante
    $.ajax({
        type: "POST",
        url: "graphiques.php",
        data: {
            catX: categoryX,
            catY: categoryY,
            nameX: namePX,
            nameY: namePY !== undefined ? namePY : 'defaultName',
            action: "tracer"
        },
        success: function(result) {
            var graph = $("#graphs");
            var canvas = graph[0];
            //Destruction du graphique précédent s'il y en avait un
            if (canvas) {
                if (window.myChart) {
                    window.myChart.destroy();
                }
            }
            
            var items = JSON.parse(result);
            
            //Si le type de grapphique est "Scatter" (Nuage de points), pas utilisé pour l'instant car pas encore au point ais serait utilisé à terme
            if(graphType == "scatter"){
                var dataX= [];
                var dataY= [];
                items.forEach(function(element) {
                    labels.push(element[Object.keys(element)[0]]);
                    dataX.push(element[Object.keys(element)[2]]);
                    dataY.push(element[Object.keys(element)[1]]);
                });
            } //Pour les autres types de graphiques que l'on a actuellement
            else{
                items.forEach(function(element) {
                    //On répartie les éléments dans les listes labels et data suvant le nombre d'éléments dans chaque ligne de la réponse
                    if(Object.keys(element).length == 2){
                        labels.push(element[Object.keys(element)[0]]);
                        data.push(element[Object.keys(element)[1]]);
                    }
                    else{
                        labels.push(element[Object.keys(element)[0]]+" ("+element[Object.keys(element)[2]]+")");
                        data.push(element[Object.keys(element)[1]]);
                    }
                });
            }
            
            //Fonction qui génère une couleur au hasard
            function generateRandomColor(alpha) {
                var r = Math.floor(Math.random() * 256);
                var g = Math.floor(Math.random() * 256);
                var b = Math.floor(Math.random() * 256);
                return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
            }
        
            var backgroundColors = [];
            var borderColors = [];
            //Pour chaque variable dans data (donc pour chaque entité qui sera dans la légende), on crée une couleur au hasard
            for (var i = 0; i < data.length; i++) {
                backgroundColors.push(generateRandomColor(0.2));
                borderColors.push(generateRandomColor(1));
            }
            
            //Mise en forme des données récupérées
            var formattedData = {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: borderWidth
                }]
            };
            
            //Création du graphique
            var canvas = document.getElementById('graphs');
            var ctx = canvas.getContext('2d');
            window.myChart = new Chart(ctx, {
                type: graphType,
                data: formattedData,
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    width: 800,
                    height: 800,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            });
        },
        error: function(xhr, status, error) {
            console.log("Erreur lors de la requête AJAX: " + status + " - " + error);
        }
    })
}
