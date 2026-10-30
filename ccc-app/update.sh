#!/bin/bash

echo "¿Deseas ejecutar las acciones '1- ANTES DE CODIFICAR' o '2- DESPUÉS DE CODIFICAR'? (1/2)"
read eleccion

if [ "$eleccion" = "1" ]; then
    echo "Ejecutando acciones 'ANTES DE CODIFICAR'..."
    git status
    git pull
elif [ "$eleccion" = "2" ]; then
    echo "Ejecutando acciones 'DESPUÉS DE CODIFICAR'..."
    echo "Introduce tu comentario:"
    read comentario
    git status
    git pull --no-rebase
    git add .
    git commit -m "$comentario"
    git push
else
    echo "Elección no reconocida. Por favor, elige 'antes' o 'despues'."
fi
