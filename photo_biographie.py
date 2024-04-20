import wikipedia
import pymysql

# Connexion à la base de données
conn = pymysql.connect(host='localhost', user='root', password='', database='prix_nobel')
cursor = conn.cursor()


def get_wikipedia_image_url(first_name, last_name):
    try:
        # Recherche sur Wikipedia en utilisant le prénom et le nom
        query = f"{first_name} {last_name}"
        page = wikipedia.page(query)

        # Récupérer l'URL de la première image de la page (si disponible)
        image_url = page.images[0] if page.images else None

        return image_url
    except wikipedia.exceptions.PageError:
        print(f"La page Wikipedia pour {query} n'a pas été trouvée.")
        return None
    except wikipedia.exceptions.DisambiguationError:
        print(f"La recherche pour {query} est ambiguë.")
        return None


try:
    # Sélectionner les prénoms et noms de la table nomine
    cursor.execute("SELECT Prénom, Nom FROM nomine")

    # Parcourir les résultats et rechercher les images correspondantes sur Wikipedia
    for row in cursor.fetchall():
        first_name, last_name = row
        image_url = get_wikipedia_image_url(first_name, last_name)

        # Mettre à jour la base de données avec l'URL de l'image
        if image_url:
            cursor.execute("UPDATE nomine SET Photos = %s WHERE Prénom = %s AND Nom = %s",
                           (image_url, first_name, last_name))
            conn.commit()
            print(f"URL de l'image pour {first_name} {last_name} ajoutée avec succès : {image_url}")
        else:
            print(f"Aucune image trouvée pour {first_name} {last_name}")

except Exception as e:
    print("Erreur lors de la récupération des données depuis la base de données :", e)

finally:
    # Fermer la connexion à la base de données
    cursor.close()
    conn.close()