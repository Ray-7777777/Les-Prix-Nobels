$("#boutonRecherche").click(function(e){
    e.preventDefault();
    var filtres = $("#activeFilters");
    var params = {};

    filtres.children().each(function() {
        var category = $(this).data("category");
        var name = $(this).data("name");
        
        if (params.hasOwnProperty(category)){
            params[category].push(name);
        }
        else{
            params[category] = name;
        }
    });

    var recherche = $("#bar").val();

    $.ajax({
        type: "POST",
        url: "rechercher.php",
        data: {
            "recherche": recherche,
            "filtres": params
        },
        success: function(result) {
            contenu= $(".contenu");
            var items = JSON.parse(result);
            var text = "<div class='article'> \n <ul> ";
            items.forEach(function(element) {
                text += "<li><div class='titre'>"+element[titre]+"</div></li>";
            });
            text += "</ul> </div>";
            contenu.append(text);
        },
        error: function(xhr, status, error) {
            console.log("Erreur lors de la requête AJAX: " + status + " - " + error);
        }
    })
})