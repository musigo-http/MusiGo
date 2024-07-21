import yt_dlp
import subprocess
import ssl
import os
from moviepy.editor import VideoFileClip

# Ignorer les erreurs de vérification du certificat SSL
ssl._create_default_https_context = ssl._create_unverified_context

# Lire l'URL de la vidéo YouTube depuis file.json
with open("file.json", "r") as url_file:
    read = url_file.read().strip()
    lien_final = f"https://www.youtube.com/watch?v={read}"

# Options pour yt-dlp
ydl_opts = {
    'format': 'best',
    'outtmpl': 'audio.mp4',
}

# Télécharger la vidéo en utilisant yt-dlp
with yt_dlp.YoutubeDL(ydl_opts) as ydl:
    ydl.download([lien_final])

# Supprimer le fichier JSON
subprocess.run("rm -r file.json", shell=True, capture_output=True, text=True)

# Chemin vers le fichier MP4 téléchargé
chemin_mp4 = "audio.mp4"

# Charger la vidéo
video = VideoFileClip(chemin_mp4)

# Chemin pour sauvegarder le fichier MP3
chemin_mp3 = "audio.mp3"

# Convertir la vidéo en audio
video.audio.write_audiofile(chemin_mp3)

# Supprimer le fichier MP4
subprocess.run("rm -r audio.mp4", shell=True, capture_output=True, text=True)

# Créer un répertoire, si besoin
subprocess.run("mkdir downloads", shell=True, capture_output=True, text=True)