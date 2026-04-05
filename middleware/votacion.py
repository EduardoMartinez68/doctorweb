import pyautogui
from pynput import mouse

# Obtener tamaño de pantalla
screen_width, screen_height = pyautogui.size()

# Contadores de puntuación
scores = {
    "arriba_izquierda": 0,
    "arriba_derecha": 0,
    "abajo_izquierda": 0,
    "abajo_derecha": 0
}

def detectar_cuadrante(x, y):
    if x < screen_width / 2 and y < screen_height / 2:
        return "arriba_izquierda"
    elif x >= screen_width / 2 and y < screen_height / 2:
        return "arriba_derecha"
    elif x < screen_width / 2 and y >= screen_height / 2:
        return "abajo_izquierda"
    else:
        return "abajo_derecha"

def on_click(x, y, button, pressed):
    if pressed:
        cuadrante = detectar_cuadrante(x, y)
        scores[cuadrante] += 1
        
        print("\n📊 Puntuación actual:")
        for k, v in scores.items():
            print(f"{k}: {v}")

# Listener del mouse
with mouse.Listener(on_click=on_click) as listener:
    print("🖱️ Haz clic en cualquier parte de la pantalla para votar...")
    listener.join()