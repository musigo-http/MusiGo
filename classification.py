import librosa
import numpy as np

def analyser_genre(path):
    y, sr = librosa.load(path, sr=None)

    # Features audio simples
    tempo, _ = librosa.beat.beat_track(y, sr=sr)
    zcr = librosa.feature.zero_crossing_rate(y)[0].mean()
    centroid = librosa.feature.spectral_centroid(y=y, sr=sr)[0].mean()
    mfcc1 = librosa.feature.mfcc(y=y, sr=sr, n_mfcc=1)[0].mean()

    print(f"Tempo : {tempo:.2f} BPM | ZCR : {zcr:.4f} | Centroid : {centroid:.2f} | MFCC1 : {mfcc1:.2f}")

    # Classification heuristique
    if tempo < 115 and zcr < 0.08 and mfcc1 < -100:
        return "Rap"
    elif tempo < 90 and centroid < 2000 and mfcc1 > -80:
        return "RnB"
    elif tempo > 125 and centroid > 3000 and zcr > 0.1:
        return "Électro"
    elif 90 <= tempo <= 140 and 1500 <= centroid <= 3500 and zcr > 0.08:
        return "Rock"
    elif tempo < 80 and centroid < 1500 and mfcc1 < -120:
        return "Musique Classique"
    elif tempo < 100 and 1800 <= centroid <= 3000 and zcr < 0.05:
        return "Jazz"
    elif tempo > 140 and zcr > 0.15 and mfcc1 < -90:
        return "Metal"
    elif 100 <= tempo <= 130 and centroid > 2500 and mfcc1 > -90:
        return "Pop"
    elif tempo < 100 and centroid < 2000 and zcr > 0.08:
        return "Reggae"
    else:
        return "Inclassable / Mixte"

# Exemple d'utilisation
print(analyser_genre("ton_fichier.mp3"))