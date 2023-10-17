# Pregundatos

Pregundatos es una pagina web inspirada en el clasico juego Preguntados, permite a los usuarios logueados jugar partidas en la que tendran que contestar una serie de preguntas de diversas categorias para ir sumando puntos.
```
Estado: en curso ...
```

![Logo](/public/presentacion/home.png)

## Indice
* [Funcionalidades principales](#funcionalidades-principales)
* [Objetivo](#objetivo)
* [Screenshots](#screenshots)
* [Lenguajes](#-Lenguajes)
* [Como ejecutar](#como-ejecutar)
* [Colaboradores](#colaboradores)

## Funcionalidades principales
- Jugar partida
- Visualizar rancking global
- Visualizar historial de partidas
- Ingresar al perfil de otros usuarios
- Estadisticas del jugador

## Objetivo
El proyecto se realizó con el objetivo de desarrollar una pagina con el patron MVC (Modelo-Vista-Controlador) en PHP y empezar a implementar AJAX y Mustache.

## Screenshots
![Logo](/public/presentacion/partida.png)
![Logo](/public/presentacion/perder.png)

## 🛠 Lenguajes
HTML, CSS, JavaScript, Bootstrap, PHP, Mustache, AJAX.

## Como ejecutar
El proyecto debe clonarse en la carpeta htdocs del xampp.
```
$ git clone https://github.com/rociocrespo200/Preguntados
```
Ver la pagina
```
localhost:(port)/
```

Si no desea guardar el archivo directamente en el htdoc puede modificar temporalmente la configuracion de apache en el archivo httpd.conf agregando el directorio que quiera
```
DocumentRoot "D:/xampp/htdocs"
<Directory "D:/xampp/htdocs">
```
Puede modificarlo por;
```
DocumentRoot "D:/xampp/htdocs/pregundatos"
<Directory "D:/xampp/htdocs/pregundatos">
```

La base de datos se encuentra en la carpeta /public/data_preguntados.sql, la conexion a la base esta configurada por defecto en el usuario "root" y contraseña vacia, si se quiere cambiar puede hacerlo desde el archivo config/configuration.ini 
```
servername=localhost
username=root
password=""
dbname=preguntados
```


## Autores
- Rocio Belen Crespo - https://www.github.com/rociocrespo200
- Karen Nina Coela - https://github.com/Karen-nina
- Duilio Martin Rubio - https://github.com/DuRubio
- Emiliano Javier Roldán - https://github.com/EmilianoRold4n
