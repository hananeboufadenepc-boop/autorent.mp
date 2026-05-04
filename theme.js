function setMode(mode){
    // applique le mode sur tout le site
    document.body.classList.toggle("light", mode === "light");

    // sauvegarde dans le navigateur
    localStorage.setItem("mode", mode);
}

function toggleMode(){
    const current = localStorage.getItem("mode") || "dark";
    const newMode = current === "dark" ? "light" : "dark";

    setMode(newMode);
}

// applique automatiquement le mode à chaque page
window.addEventListener("load", () => {
    const mode = localStorage.getItem("mode") || "dark";
    setMode(mode);
});