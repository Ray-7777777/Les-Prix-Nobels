import sys
import json
from recommander_prix_nobel import recommended_prix_nobel
import os


def recommander_prix_nobel_historique(historique):
    recommandations = []
    for element in historique:  # Parcours de chaque élément de l'historique
        recommandations+= recommended_prix_nobel(element)
    
    # Compter les occurrences des ID des prix Nobel recommandés
    id_occurrences = {}
    for recommandation in recommandations:
        if recommandation in id_occurrences:
            id_occurrences[recommandation] += 1
        else:
            id_occurrences[recommandation] = 1
    
    # Sélectionner les trois ID les plus fréquents
    top_recommendations = sorted(id_occurrences, key=id_occurrences.get, reverse=True)[:3]
    return top_recommendations

if __name__ == "__main__":
    # Obtenir le répertoire du script Python en cours d'exécution
    repertoire_script = os.path.dirname(os.path.abspath(__file__))

    # Lire l'historique des pages depuis le fichier JSON en argument
    with open(os.path.join(repertoire_script, 'historique.json'), 'r') as file:
        historique = json.load(file)

    # Appeler la fonction de recommandation avec l'historique en tant qu'argument
    recommandations = recommander_prix_nobel_historique(historique)

    # Enregistrer les recommandations dans un fichier JSON de sortie
    with open(os.path.join(repertoire_script, 'output_historique.json'), 'w') as file:
        json.dump({"recommended_indices": recommandations}, file)
