function mauvaisNomDeDomaine(){
    if(location.host !== "machatvente.com" && location.host !== "www.machatvente.com"){
        document.querySelector("html").remove();
    }
};
mauvaisNomDeDomaine();

function mauvaisProtocol(){
    if(location.protocol !== "https:"){
        location.protocol="https:";
    }
};
mauvaisProtocol();