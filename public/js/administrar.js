document.addEventListener("DOMContentLoaded", function() {
    var selectElements = document.querySelectorAll('.mi-selector');

    for (var i = 0; i < selectElements.length; i++) {
        var selectElement = selectElements[i];
        selectElement.classList.add("select2"); // Agregar la clase "select2" para la apariencia
    }
});


//IMAGEN

document.addEventListener("DOMContentLoaded", function () {
    const editarImagen = document.getElementById("editarImagen");
    const fileInput = document.getElementById("fileInput");

    // Agrega un controlador de eventos para el clic en la imagen
    editarImagen.addEventListener("click", function () {
        fileInput.click(); // Simula hacer clic en el elemento de entrada de archivo
    });

    // Agrega un controlador de eventos para detectar cuando se selecciona un archivo
    fileInput.addEventListener("change", function () {
        const selectedFile = fileInput.files[0];
        if (selectedFile) {
            // Aquí puedes realizar acciones con el archivo seleccionado
            console.log("Archivo seleccionado:", selectedFile.name);
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.getElementById("fileInput");
    const imagenEnviada = document.getElementById("imagen_enviada");

    // Agrega un controlador de eventos para detectar cuando se selecciona un archivo
    fileInput.addEventListener("change", function () {
        const selectedFile = fileInput.files[0];
        if (selectedFile) {
            // Crea una URL válida para el archivo seleccionado
            const objectURL = URL.createObjectURL(selectedFile);

            // Establece el atributo "src" de la imagen con la URL del archivo
            imagenEnviada.src = objectURL;
        }
    });
});

