$(".dropdown-item.BFiltre").click(function(e){
    e.preventDefault();
    var $this = $(this);
    var buttonText = $(this).text();
    var name = $(this).attr("name");
    $.ajax({
        type: "POST",
        url: "filtres.php",
        data: {
            id: $(this).attr('id')
        },
        success: function(result) {
            filtres= $(".filters");
            var items = JSON.parse(result);
            var text = "<div class='dropdown' id='sousFiltre'> \n <a class='btn btn-secondary dropdown-toggle' href='#' role='button' id='dropdownMenuLink' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+ buttonText +"</a> \n <div class='dropdown-menu' data-category='"+buttonText+"'> ";
            items.forEach(function(element) {
                text += "<button class='dropdown-item SFiltre' type='button' id='Filtre"+element[name]+"'>"+element[name]+"</button>\n";
            });
            $this.prop("disabled", "true");
            text += "</div> </div>";
            filtres.append(text);
        },
        error: function(xhr, status, error) {
            console.log("Erreur lors de la requête AJAX: " + status + " - " + error);
        }
    })

})

$(".filters").on("click", ".dropdown-item.SFiltre", function(e){
    e.preventDefault();
    var text = "<div class='FiltreCroix'><p data-category='"+$(this).parent().data("category")+"' data-name='"+$(this).text()+"'>"+$(this).text()+"</p><span class='float-right clickable close-icon'> </span></div>";
    $("#activeFilters").append(text);
    $(this).prop("disabled", true);
})

$("#activeFilters").on("click", ".close-icon", function(e){
    var name = $(this).prev().data("name");
    $("#Filtre"+name).prop("disabled", false);
    $(this).parent().remove();
})