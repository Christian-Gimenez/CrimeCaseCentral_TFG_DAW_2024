let boton = document.querySelector(".boton_menu_movil");
boton.addEventListener("click",function() {
    let menu = document.getElementById("menu");
    menu.classList.toggle("menu_slide_oculto");
    let img = document.getElementById("imagen_inicial_navbar");
    let h1 = document.getElementById("titulo_movil");
    if(h1)h1.classList.toggle("oculto");

    if(img.src.includes("CCC-logo-mobile")){
        img.src = "../imgs/LOGO_DEFINITIVO.png";
        img.style.width = "55px";
        img.style.height = "55px";
    }else{
        img.src = "../imgs/CCC-logo-mobile.png";
        img.style.width = "100px";
        img.style.height = "50px";
    }
})
