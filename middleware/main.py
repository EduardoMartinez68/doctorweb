import cv2
import mediapipe as mp
import numpy as np
import time

# Inicializar mediapipe
mp_face_mesh = mp.solutions.face_mesh
face_mesh = mp_face_mesh.FaceMesh(
    refine_landmarks=True,
    max_num_faces=1
)

# Cámara
cap = cv2.VideoCapture(0)

# Contadores
zonas = {
    "arriba_izquierda": 0,
    "arriba_derecha": 0,
    "abajo_izquierda": 0,
    "abajo_derecha": 0
}

ultimo_tiempo = time.time()

def obtener_centro(ojo):
    x = int(np.mean([p[0] for p in ojo]))
    y = int(np.mean([p[1] for p in ojo]))
    return x, y

while True:
    ret, frame = cap.read()
    if not ret:
        break

    frame = cv2.flip(frame, 1)  # espejo (más natural)
    h, w, _ = frame.shape

    rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    resultado = face_mesh.process(rgb)

    if resultado.multi_face_landmarks:
        face_landmarks = resultado.multi_face_landmarks[0]

        puntos = []
        for lm in face_landmarks.landmark:
            puntos.append((int(lm.x * w), int(lm.y * h)))

        # Puntos más precisos de ojos
        ojo_izq = [puntos[i] for i in [33, 160, 158, 133, 153, 144]]
        ojo_der = [puntos[i] for i in [362, 385, 387, 263, 373, 380]]

        x_izq, y_izq = obtener_centro(ojo_izq)
        x_der, y_der = obtener_centro(ojo_der)

        # Centro de mirada (aproximado)
        x = int((x_izq + x_der) / 2)
        y = int((y_izq + y_der) / 2)

        # Dibujar ojos
        cv2.circle(frame, (x_izq, y_izq), 3, (255, 0, 0), -1)
        cv2.circle(frame, (x_der, y_der), 3, (255, 0, 0), -1)

        # Dibujar punto mirada
        cv2.circle(frame, (x, y), 5, (0, 255, 0), -1)

        # Tiempo
        ahora = time.time()
        delta = ahora - ultimo_tiempo
        ultimo_tiempo = ahora

        # Determinar zona
        if x < w // 2 and y < h // 2:
            zonas["arriba_izquierda"] += delta
            zona_actual = "Arriba Izquierda"
        elif x >= w // 2 and y < h // 2:
            zonas["arriba_derecha"] += delta
            zona_actual = "Arriba Derecha"
        elif x < w // 2 and y >= h // 2:
            zonas["abajo_izquierda"] += delta
            zona_actual = "Abajo Izquierda"
        else:
            zonas["abajo_derecha"] += delta
            zona_actual = "Abajo Derecha"

        # Texto
        cv2.putText(frame, zona_actual, (30, 50),
                    cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 255), 2)

    # Dibujar cuadrantes
    cv2.line(frame, (w//2, 0), (w//2, h), (255, 255, 255), 1)
    cv2.line(frame, (0, h//2), (w, h//2), (255, 255, 255), 1)

    cv2.imshow("Eye Tracker", frame)

    if cv2.waitKey(1) & 0xFF == 27:
        break

cap.release()
cv2.destroyAllWindows()

# Resultados
print("\nTiempo por zona:")
for zona, tiempo in zonas.items():
    print(f"{zona}: {tiempo:.2f} segundos")