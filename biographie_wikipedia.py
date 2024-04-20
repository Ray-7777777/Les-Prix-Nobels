import wikipedia
import csv


# Configuration de la langue de recherche (français)
wikipedia.set_lang("fr")

# Fonction pour récupérer la biographie d'une personne en combinant son nom et prénom
def get_biography(nom, prenom):
    try:
        # Recherche du titre de la page Wikipedia
        search_query = f"{prenom} {nom}"
        page_title = wikipedia.search(search_query)[0]

        # Récupération de la page Wikipedia
        page = wikipedia.page(page_title)

        # Retourner le contenu de la page (la biographie)
        return page.content
    except Exception as e:
        print(f"Erreur lors de la récupération de la biographie de {prenom} {nom}: {str(e)}")
        return None

# Fonction pour ajouter une colonne "Biographie" au fichier CSV
def add_biography_to_csv(input_filename, output_filename):
    with open(input_filename, mode='r', newline='', encoding='utf-8') as input_file:
        reader = csv.DictReader(input_file)
        fieldnames = reader.fieldnames + ['Biographie']

        with open(output_filename, mode='w', newline='', encoding='utf-8') as output_file:
            writer = csv.DictWriter(output_file, fieldnames=fieldnames)
            writer.writeheader()

            for row in reader:
                biographie = get_biography(row['Nom'], row['Prenom'])
                if biographie is not None:
                    row['Biographie'] = biographie
                else:
                    row['Biographie'] = "Biographie non trouvée"
                writer.writerow(row)

# Nom du fichier CSV d'entrée et de sortie
input_filename = 'nomine.csv'
output_filename = 'output.csv'

# Ajouter la colonne "Biographie" au fichier CSV
add_biography_to_csv(input_filename, output_filename)

print(f"La colonne 'Biographie' a été ajoutée au fichier '{output_filename}'.")