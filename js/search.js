document.addEventListener("DOMContentLoaded", function() {

    document.getElementById("formularioDeRegistroBus").addEventListener("submit", function(e){
        const keywordInput = document.getElementById("keyword");
        const keyword = keywordInput.value.trim();
    
        if (keyword !== keywordInput.value || keyword === "") {
            keywordInput.classList.add("is-invalid");
            keywordInput.nextElementSibling.textContent = "El registro no debe contener espacios en blanco";
            e.preventDefault();
        } else {
            keywordInput.classList.remove("is-invalid");
            keywordInput.nextElementSibling.textContent = "";
        }
    });

});
