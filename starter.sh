#!/bin/bash

if ! command -v screen &> /dev/null; then
    echo "Screen nie jest zainstalowany. Zainstaluj go przed uruchomieniem skryptu."
    exit 1
fi

if [ ! -f "App.php" ]; then
    echo "Plik App.php nie istnieje. Upewnij się, że plik jest obecny w bieżącym katalogu."
    exit 1
fi

start() {
    if screen -list | grep -q "app_session"; then
        echo "Sesja 'app_session' już istnieje."
        exit 1
    fi
    screen -dmS app_session php App.php

    echo "Plik App.php został uruchomiony w sesji screen o nazwie 'app_session'."
}

stop() {
    if ! screen -list | grep -q "app_session"; then
        echo "Sesja 'app_session' nie istnieje."
        exit 1
    fi
    screen -S app_session -X quit

    echo "Sesja 'app_session' została zatrzymana."
}


if [ $# -ne 1 ]; then
    echo "Dostępne komendy: <start/stop>"
    exit 1
fi

# Wykonaj odpowiednią akcję na podstawie podanej komendy
case "$1" in
    start)
        start
        ;;
    stop)
        stop
        ;;
    *)
        echo "Nieznana komenda. Dostępne komendy: start, stop."
        exit 1
        ;;
esac
