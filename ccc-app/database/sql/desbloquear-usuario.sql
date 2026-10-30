USE mydb;

UPDATE usuarios
SET intentos = 0, tiempo_bloqueado = NULL
WHERE nombre = 'nombre_usuario'; #Cambiar por el nombre de usuario a desbloquear
