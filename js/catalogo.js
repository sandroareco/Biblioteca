const fecha = document.getElementById("fecha");

setInterval(()=>{
    let fechaAct = new Date();
    let fechaHora = fechaAct.toLocaleString();
    fecha.textContent = fechaHora;
},1000);