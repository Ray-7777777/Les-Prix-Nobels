function recherche(event){
    debugger;
    event.preventDefault();

    const data = new FormData();
    const recherche = document.querySelector("#bar");
    data.append("recherche", recherche.value);
    const requete = new XMLHttpRequest();
    requete.open("POST", "rechercher.php");

    requete.onload = function(){
        if (requete.status === 200){
            recherche.value="";
            const reponse = JSON.parse(requete.responseText);
            const resultat = document.querySelector(".contenu");
            resultat.innerHTML = "";
            resultat.innerHTML += "<ul>"; 
            for (let item of reponse){
                resultat.innerHTML += `<li>${item}</li>`;
            }
            resultat.innerHTML += "</ul>";
        }
        else {
            console.error("erreur", requete.status);
        }
    }
    requete.send(data);
}