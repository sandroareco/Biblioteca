document.addEventListener("DOMContentLoaded", function(){

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

    function isValidEmail(email) {
        const expresionReg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return expresionReg.test(email);
    }

    const formulario = document.getElementById("formularioDeRegistro");
    const nombreInput = document.getElementById("nombres");
    const apellidoInput = document.getElementById("apellidos");

    formulario.addEventListener("submit",function(e){
        const nombre = nombreInput.value.trim();
        const apellido = apellidoInput.value.trim();
        const password = document.getElementById("password").value;
        const confirmedPassInput = document.getElementById("confirmarPassword");
        const confirmedPass = document.getElementById("confirmarPassword").value;
        const correo = document.getElementById("email").value;
        const correoInput = document.getElementById("email");

        const nombreValido = validarCampo(nombreInput);
        const apellidoValido = validarCampo(apellidoInput);

        if (!nombreValido || !apellidoValido) {
            e.preventDefault();
        }

        if (nombre !== nombreInput.value || nombre === "") {
            nombreInput.classList.add("is-invalid");
            nombreInput.nextElementSibling.textContent = "El registro no debe contener espacios en blanco";
            e.preventDefault();
        } else {
            nombreInput.classList.remove("is-invalid");
            nombreInput.nextElementSibling.textContent = "";
        }

        if (apellido !== apellidoInput.value || apellido === "") {
            apellidoInput.classList.add("is-invalid");
            apellidoInput.nextElementSibling.textContent = "El registro no debe contener espacios en blanco";
            e.preventDefault();
        } else {
            apellidoInput.classList.remove("is-invalid");
            apellidoInput.nextElementSibling.textContent = "";
        }

        if (!isValidEmail(correo)) {
            correoInput.classList.add("is-invalid");
            correoInput.nextElementSibling.textContent = "Formato de email incorrecto";
            e.preventDefault();
        } else {
            correoInput.classList.remove("is-invalid");
            correoInput.nextElementSibling.textContent = "";
        }

        if(password!==confirmedPass){
            confirmedPassInput.classList.add("is-invalid");
            e.preventDefault();
        }else{
            confirmedPassInput.classList.remove("is-invalid");
        }
    });

    nombreInput.addEventListener("input", function() {
        validarCampo(nombreInput);
    });

    apellidoInput.addEventListener("input", function() {
        validarCampo(apellidoInput);
    });

});


