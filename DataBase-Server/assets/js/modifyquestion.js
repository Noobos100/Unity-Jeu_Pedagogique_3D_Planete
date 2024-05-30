const inputOrbit = document.getElementById("orbit");
const inputRotation = document.getElementById("rotation");
const inputMargeOrbit = document.getElementById("margin-orbit");
const inputMargeRotation = document.getElementById("margin-rotation");

const isOrbitable = document.getElementById("orbitable");
const isRotatable = document.getElementById("rotatable");
const hasOrbitMargin = document.getElementById("orbit-margin");
const hasRotationMargin = document.getElementById("rotation-margin");

inputOrbit.disabled = isOrbitable.checked;
inputRotation.disabled = isRotatable.checked;
inputMargeOrbit.disabled = hasOrbitMargin.checked;
inputMargeRotation.disabled = hasRotationMargin.checked;

isOrbitable.addEventListener("change", () => {
    inputOrbit.disabled = isOrbitable.checked;
    inputOrbit.value = "";
})

isRotatable.addEventListener("change", () => {
    inputRotation.disabled = isRotatable.checked;
    inputRotation.value = "";
})

hasOrbitMargin.addEventListener("change", () => {
    inputMargeOrbit.disabled = hasOrbitMargin.checked;
    inputMargeOrbit.value = "";
})

hasRotationMargin.addEventListener("change", () => {
    inputMargeRotation.disabled = hasRotationMargin.checked;
    inputMargeRotation.value = "";
})

function confirmLeave() {
    if (confirm("Voulez-vous vraiment quitter sans sauvegarder?")) {
        window.location.href = "/manage-questions";
    }
}