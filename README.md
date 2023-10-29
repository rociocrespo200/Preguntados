# Pregundatos

Pregundatos es una pagina web inspirada en el clasico juego Preguntados, permite a los usuarios logueados jugar partidas en la que tendran que contestar una serie de preguntas de diversas categorias para ir sumando puntos.
```
Estado: en curso ...
```

![Logo](/public/Presentacion/home.png)

## Indice
* [Funcionalidades principales](#funcionalidades-principales)
* [Objetivo](#objetivo)
* [Screenshots](#screenshots)
* [Lenguajes](#-lenguajes)
* [Como ejecutar](#como-ejecutar)
* [Autores](#Autores)

## Funcionalidades principales
1. **Jugar Partida:**
   - Los usuarios no verán preguntas ya respondidas.
   - La dificultad de las preguntas se ajusta según el rendimiento.

2. **Sistema de Puntaje:**
   - Asignación de puntos basada en la dificultad de las preguntas.
   - Ajuste dinámico de la dificultad según el porcentaje de respuestas correctas.

3. **Trampitas:**
   - Opción para comprar pistas que revelen la respuesta correcta.
   - Acceso a trucos que pueden proporcionar información adicional.

4. **Temporizador de Respuesta:**
   - Establecimiento de un límite de tiempo de 10 segundos para responder cada pregunta.

5. **Ranking Global:**
   - Generación de un ranking global de jugadores.
   - Uso de códigos QR para acceder directamente a los perfiles de los jugadores.

6. **Historial de Partidas:**
   - Ordenamiento del historial por fecha o puntuación.
   - Representación gráfica del rendimiento por categorías.

7. **Perfil de Usuario:**
   - Edición de la información de la cuenta de usuario.
   - Visualización de estadísticas detalladas del rendimiento del jugador.
  
### ROL EDITOR
1. **Administrar Preguntas en la Base de Datos:**
   - Agregar, eliminar y modificar preguntas existentes en la base de datos.
   - Asegurar que la base de datos esté actualizada y contenga preguntas relevantes y precisas.

2. **Administrar Reportes de Preguntas:**
   - Revisar las preguntas que han sido reportadas por los usuarios.
   - Tomar medidas adecuadas como aprobar o eliminar las preguntas en función de la revisión.

3. **Administrar Solicitudes de Nuevas Preguntas:**
   - Evaluar y aprobar las preguntas sugeridas por los usuarios.
   - Mantener una comunicación clara con los usuarios para proporcionar retroalimentación sobre sus sugerencias.

### ROL ADMINISTRADOR
1. **Estadísticas de Jugadores:**
   - Cantidad total de usuarios registrados en la aplicación.
   - Número de usuarios nuevos que se han unido recientemente.
   - Distribución de usuarios por país, género y edad.

2. **Estadísticas del Juego:**
   - Número total de partidas jugadas en la aplicación.
   - Cantidad de preguntas disponibles en la base de datos.
   - Porcentaje de preguntas acertadas e incorrectas durante las partidas.

## Objetivo
El proyecto se realizó con el objetivo de desarrollar una pagina con el patron MVC (Modelo-Vista-Controlador) en PHP y empezar a implementar AJAX y Mustache.

## Screenshots
![Logo](/public/Presentacion/partida.png)
![Logo](/public/Presentacion/perder.png)

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
