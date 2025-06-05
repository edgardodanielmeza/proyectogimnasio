@echo off
:: Batch file avanzado para Git con manejo de errores
:: ------------------------------------------------

setlocal enabledelayedexpansion

:: Verificar si es repositorio Git
git status >nul 2>&1
if errorlevel 1 (
    echo ERROR: No es un repositorio Git o no tienes Git instalado
    pause
    exit /b
)

:: Obtener fecha/hora formateada
for /f "tokens=1-6 delims=/:. " %%a in ('echo %date% %time%') do (
    set dia=%%a
    set mes=%%b
    set anio=%%c
    set hora=%%d
    set minuto=%%e
)
set fecha=!dia!/!mes!/!anio!
set hora_completa=!hora!:!minuto!

:: Commit message
set comentario="Commit automático - !fecha! !hora_completa!"

:: Ejecutar comandos Git
echo Subiendo cambios a Git...
git add . && (
    git commit -m !comentario! && (
        git push origin master && (
            echo -----------------------------------------------
            echo ¡EXITO! Subida completada
            echo Fecha: !fecha!
            echo Hora: !hora_completa!
            echo -----------------------------------------------
        )
    )
)

if errorlevel 1 (
    echo ERROR: Fallo en el proceso Git
    echo Revisa los cambios pendientes o conexión
)

pause