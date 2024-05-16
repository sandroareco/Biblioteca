document.addEventListener("DOMContentLoaded", function(){
    function isValidEmail(email) {
        const expresionReg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return expresionReg.test(email);
    }

    const formulario = document.getElementById("formularioDeRegistro");

    formulario.addEventListener("submit",function(e){
        const correoInput = document.getElementById("email");
        const correo = correoInput.value;

        if (!isValidEmail(correo)) {
            correoInput.classList.add("is-invalid");
            correoInput.nextElementSibling.textContent = "Formato de email incorrecto";
            e.preventDefault();
        } else {
            correoInput.classList.remove("is-invalid");
            correoInput.nextElementSibling.textContent = "";
        }
    });
});