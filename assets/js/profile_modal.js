const openModal = document.getElementById("openModal");
const closeModal = document.getElementById("closeModal");
const modal = document.getElementById("profileModal");
const dropArea = document.getElementById("dropArea");
const fileInput = document.getElementById("fileInput");

if (openModal) {
    openModal.onclick = () => {
        modal.style.display = "flex";
    };
}

if (closeModal) {
    closeModal.onclick = () => {
        modal.style.display = "none";
    };
}

window.onclick = (e) => {
    if (e.target === modal) {
        modal.style.display = "none";
    }
};

if (dropArea) {
    dropArea.onclick = () => fileInput.click();

    dropArea.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropArea.style.background = "#eef";
    });

    dropArea.addEventListener("dragleave", () => {
        dropArea.style.background = "white";
    });

    dropArea.addEventListener("drop", (e) => {
        e.preventDefault();
        fileInput.files = e.dataTransfer.files;
        dropArea.style.background = "white";
    });
}
