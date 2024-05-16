document.addEventListener("DOMContentLoaded", function() {

    function soloLetrasConEspacios(value) {
        const regex = /^[A-Za-z\s]+$/;
        return regex.test(value);
    }
    
    function validarCampo(input) {
        let valor = input.value.trim();
        if (valor === "") {
            input.classList.add("is-invalid");
            input.nextElementSibling.textContent = "Este campo es obligatorio";
            return false;
        } else if (!soloLetrasConEspacios(valor)) {
            input.classList.add("is-invalid");
            input.nextElementSibling.textContent = "Debe ingresar solo letras";
            return false;
        } else {
            input.classList.remove("is-invalid");
            input.nextElementSibling.textContent = "";
            return true;
        }
    }
    
    const formulario = document.getElementById("formularioDeRegistroAgr");
    const autorInput = document.getElementById("autor");
    const generoInput = document.getElementById("genero");

    formulario.addEventListener("submit", function(e) {
        const tituloInput = document.getElementById("titulo");
        const titulo = tituloInput.value.trim();
        const autor = autorInput.value.trim();
        const editorialInput = document.getElementById("editorial");
        const editorial = editorialInput.value.trim();
        const genero = generoInput.value.trim();
        const disponibilidadInput = document.getElementById("disponibilidad");
        const disponibilidad = disponibilidadInput.value.trim();

        const autorValido = validarCampo(autorInput);
        const generoValido = validarCampo(generoInput);

        if (titulo !== tituloInput.value || titulo === "") {
            tituloInput.classList.add("is-invalid");
            tituloInput.nextElementSibling.textContent = "El registro no debe contener espacios en blanco";
            e.preventDefault();
        } else {
            tituloInput.classList.remove("is-invalid");
            tituloInput.nextElementSibling.textContent = "";
        }

        if (autor !== autorInput.value || autor === "") {
            autorInput.classList.add("is-invalid");
            autorInput.nextElementSibling.textContent = "El registro no debe contener espacios en blanco";
            e.preventDefault(); 
        } else if (!soloLetrasConEspacios(autor)) {
            autorInput.classList.add("is-invalid");
            autorInput.nextElementSibling.textContent = "Debe ingresar solo letras";
            e.preventDefault();
        } else {
            autorInput.classList.remove("is-invalid");
            autorInput.nextElementSibling.textContent = "";
        }

        if (editorial !== editorialInput.value || editorial === "") {
            editorialInput.classList.add("is-invalid");
            editorialInput.nextElementSibling.textContent = "La registro no debe contener espacios en blanco";
            e.preventDefault();
        } else {
            editorialInput.classList.remove("is-invalid");
            editorialInput.nextElementSibling.textContent = "";
        }

        if (genero !== generoInput.value || genero === "") {
            generoInput.classList.add("is-invalid");
            generoInput.nextElementSibling.textContent = "El registro no debe contener espacios en blanco";
            e.preventDefault();
        } else if (!soloLetrasConEspacios(genero)) {
            generoInput.classList.add("is-invalid");
            generoInput.nextElementSibling.textContent = "Debe ingresar solo letras";
            e.preventDefault();
        } else {
            generoInput.classList.remove("is-invalid");
            generoInput.nextElementSibling.textContent = "";
        }

        if (disponibilidad !== disponibilidadInput.value || disponibilidad === "") {
            disponibilidadInput.classList.add("is-invalid");
            disponibilidadInput.nextElementSibling.textContent = "El registro no debe contener espacios en blanco";
            e.preventDefault();
        } else {
            disponibilidadInput.classList.remove("is-invalid");
            disponibilidadInput.nextElementSibling.textContent = "";
        }
    });

    autorInput.addEventListener("input", function() {
        validarCampo(autorInput);
    });

    generoInput.addEventListener("input", function() {
        validarCampo(generoInput);
    });
});