$("#boutonRecherche").click(function(e){
    e.preventDefault();
    var filtres = $("#activeFilters");
    var params = {};
    contenu= $(".contenu");
    contenu.empty();

    filtres.children().each(function() {
        var category = $(this).children().eq(0).data("category");
        var name = $(this).children().eq(0).data("name");
        
        if (params.hasOwnProperty(category)) {
            params[category].push(name);
        } else {
            params[category] = [name];
        }
    });

    var recherche = $("#bar").val();

    var dataSend = {
        "recherche": recherche
    };

    if (Object.keys(params).length !== 0) {
        dataSend["filtres"] = params;
    }

    $.ajax({
        type: "POST",
        url: "rechercher.php",
        data: dataSend,
        success: function(result) {
            var items = JSON.parse(result);
            var text = "<ul> ";
            items.forEach(function(element) {
                text += "<li><div class='titre_recherche'>"+element["Prénom"]+" "+element["Nom"]+"</div><p class='minibio'>"+element["biographie"]+"</p></li>";
            });
            text += "</ul>";
            contenu.append(text);
        },
        error: function(xhr, status, error) {
            console.log("Erreur lors de la requête AJAX: " + status + " - " + error);
        }
    })
})