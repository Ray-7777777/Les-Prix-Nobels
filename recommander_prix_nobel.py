import sys
import json
import datetime

# Importations des bibliothèques
import pandas as pd
from sklearn.preprocessing import StandardScaler, OneHotEncoder
from sklearn.compose import ColumnTransformer
from sklearn.pipeline import Pipeline
from sklearn.neighbors import NearestNeighbors

# Log pour vérifier que le script est bien lancé
print("Le script Python a été lancé avec succès.")

import os

# Obtenir le répertoire du script Python en cours d'exécution
repertoire_script = os.path.dirname(os.path.abspath(__file__))

# Construire le chemin relatif au fichier CSV
chemin_relatif = os.path.join(repertoire_script, 'knn', 'prix_nobel.csv')

# Lire le fichier CSV avec pandas en utilisant le chemin relatif
df = pd.read_csv(chemin_relatif)

# Drop rows with missing values for simplicity
df.dropna(inplace=True)

# Extract features
X = df[['Année', 'id_category', 'Date_de_naissance', 'Date_de_mort', 'Born_country', 'Born_city', 'Died_country', 'Died_city', 'Gender', 'biographie', 'mots_cles', 'nom_organisation', 'ville_organisation', 'pays_organisation']]

# Define categorical features
categorical_features = ['Born_country', 'Born_city', 'Died_country', 'Died_city', 'Gender', 'nom_organisation', 'ville_organisation', 'pays_organisation']

# Define numerical features
numerical_features = ['Année', 'id_category']

# Preprocessing pipeline
preprocessor = ColumnTransformer(
    transformers=[
        ('num', StandardScaler(), numerical_features),
        ('cat', OneHotEncoder(handle_unknown='ignore'), categorical_features)])

# Pipeline
pipeline = Pipeline(steps=[('preprocessor', preprocessor)])

# Fit the pipeline and transform the data
X_transformed = pipeline.fit_transform(X)

# Fit NearestNeighbors on the preprocessed data
nn_model = NearestNeighbors(n_neighbors=4)
nn_model.fit(X_transformed)

# Function to recommend laureates based on the input laureate's features
def recommended_prix_nobel(laureate_features):
    # Convert the input laureate's features into a pandas DataFrame with a single row
    input_df = pd.DataFrame([laureate_features], columns=X.columns)
    
    # Transform the input laureate's features
    input_transformed = pipeline.transform(input_df)
    
    # Find nearest neighbors
    _, indices = nn_model.kneighbors(input_transformed)
    
    # Get recommended laureates (excluding the input laureate)
    recommended_indices = indices[0][1:]  # Exclude the first index (which is the input laureate)
    recommended_laureates = df.iloc[recommended_indices]
    recommended_indices = recommended_laureates.index+1
    recommended_indices = recommended_indices.tolist()
    return recommended_indices

import os
import json

# Obtenir le répertoire du script Python en cours d'exécution
repertoire_script = os.path.dirname(os.path.abspath(__file__))

# Lire les données à partir du fichier JSON
with open(os.path.join(repertoire_script, 'input.json'), 'r') as file:
    laureate_features = json.load(file)

# Appel de la fonction pour recommander les lauréats similaires
recommended_indices = recommended_prix_nobel(laureate_features)

# Écrire les résultats dans un fichier JSON
output_data = {'recommended_indices': recommended_indices}
with open(os.path.join(repertoire_script, 'output.json'), 'w') as output_file:
    json.dump(output_data, output_file)
