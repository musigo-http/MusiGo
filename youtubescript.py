import requests

# Clé API YouTube
API_KEY = "ma super clef api"

# ID de la chaîne Ziak C.C
CHANNEL_ID = "UCBAkHJepWR2HTPE65iruihw"#channel id de ziak

# URL de base pour les requêtes à l'API
BASE_URL = "https://www.googleapis.com/youtube/v3/search"

# Liste pour stocker les titres
titles = []

# Paramètres initiaux pour récupérer les vidéos de la chaîne
params = {
    "part": "snippet",
    "channelId": CHANNEL_ID,
    "maxResults": 50,  # Maximum par requête
    "order": "date",   # Trier par date (le plus récent en premier)
    "type": "video",   # Ne récupérer que les vidéos
    "key": API_KEY
}

# Requête initiale
response = requests.get(BASE_URL, params=params)
data = response.json()

# Extraire les titres des vidéos
for item in data.get("items", []):
    titles.append(item["snippet"]["title"])

# Gérer les vidéos suivantes si nécessaire (pagination)
while "nextPageToken" in data:
    params["pageToken"] = data["nextPageToken"]
    response = requests.get(BASE_URL, params=params)
    data = response.json()
    for item in data.get("items", []):
        titles.append(item["snippet"]["title"])

# Afficher les résultats
print(f"Nombre total de vidéos récupérées : {len(titles)}")
for idx, title in enumerate(titles, start=1):
    print(f"{idx}. {title}")

i=0
for loop in range(30):
    requests.get(f"http://90.100.231.167/index.php?search={titles[i]}")#requetes a mon site pour qu'il telecharge toutes les musiques de ziak ou d'un artiste en particulier mais la en loccurence c'est ziak
    i+=1
    print(i)
#essayer d'ecrire automatiquement les titres des chanteurs que je souhaite telecharger au sein du serveur dans un fichier puis ptet les decouper genre en 30 telecharger par jour ou qlq chose comme sa