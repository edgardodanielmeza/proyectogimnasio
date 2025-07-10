@echo off
setlocal enabledelayedexpansion

rem Archivo donde se guardará el resultado
set "resultado=resultado.txt"

rem Elimina el archivo de resultados anterior si existe
if exist "%resultado%" del "%resultado%"

rem Listar archivos .php en el directorio actual
for %%f in (*.php) do (
    echo Nombre del archivo: %%f >> "%resultado%"
    type "%%f" >> "%resultado%"
    echo. >> "%resultado%"
)

echo Resultado guardado en %resultado%
endlocal
pause