# -*- coding: utf-8 -*-

import requests
from bs4 import BeautifulSoup
import urllib.parse
import yt_dlp
import subprocess
from moviepy import VideoFileClip
import os
import json

with open("musique.lst", "r") as fichier:
    contenu = fichier.read()

url = 'https://ile-reunion.org/gpt3/resultat'
data = {
        'D1': 'Option sortie audio',
        'exemple-prompt': 'Exemples de prompt',
        'xscreen': '1280',
        'yscreen': '800',
        'question': f"donne moi 1 titre musical de rap de octobre 2020 a octobre 2023 en francais je veux aussi le nom de l'artiste sans sauter de ligne c'est important donc respecte bien et je veux aussi que il n'y ai que ça dans ta réponse pas un seul autre mot rien juste 1 titre musical aimé demandé rien de plus."
    }

headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.6533.100 Safari/537.36',
        'Content-Type': 'application/x-www-form-urlencoded',
        'Accept-Language': 'fr-FR',
        'Referer': 'https://ile-reunion.org/gpt3/',
    }

response = requests.post(url, data=data, headers=headers)

if response.status_code == 200:
    html_content = response.text
    print(html_content)

    soup = BeautifulSoup(html_content, 'html.parser')
    
    texte_brut = soup.get_text(separator=' ', strip=True)
    
    p_resultat = soup.find('p', text=lambda x: x and 'Résultat :' in x)
    if p_resultat:
        suivant = p_resultat.find_next_sibling(text=True)
        if suivant:
            texte_suivant = suivant.strip()
            print(texte_suivant)
            if(texte_suivant != '"Jolie nana" Ninho'):
                requests.get("http://90.100.231.167/index.php?search="+urllib.parse.quote(texte_suivant))
            else:
                pass