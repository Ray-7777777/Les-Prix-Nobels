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
                text += "<li class='article_recherche' data-id="+element["Id_nominé"]+"><div class='titre_recherche'>"+element["Prénom"]+" "+element["Nom"]+"</div><p class='minibio'>"+element["biographie"]+"</p></li>";
            });
            text += "</ul>";
            contenu.append(text);
            localStorage.setItem('rechercheResults', JSON.stringify(items));
        },
        error: function(xhr, status, error) {
            console.log("Erreur lors de la requête AJAX: " + status + " - " + error);
        }
    })
})

$(".contenu").on("click", ".article_recherche", function(){
    window.location.href="article_wikipedia.php?id="+$(this).data("id");
});

$(document).ready(function() {
    var storedResults = localStorage.getItem('rechercheResults');
    if (storedResults) {
        var items = JSON.parse(storedResults);
        var text = "<ul> ";
        items.forEach(function(element) {
            if (element["Nom"]=="NULL"){
                text += "<li class='article_recherche' data-id="+element["Id_nominé"]+"><div class='titre_recherche'>"+element["Prénom"]+"</div><p class='minibio'>"+element["biographie"]+"</p></li>";
            }
            else{
                text += "<li class='article_recherche' data-id="+element["Id_nominé"]+"><div class='titre_recherche'>"+element["Prénom"]+" "+element["Nom"]+"</div><p class='minibio'>"+element["biographie"]+"</p></li>";
            }
            });
        text += "</ul>";
        $(".contenu").html(text);
    }
});
