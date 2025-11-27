// Modal handlers //
const modal = document.getElementById("postModal");
const openBtn = document.getElementById("openPostModal");
const closeBtn = document.querySelector(".close-modal");

openBtn.onclick = () => modal.style.display = "flex";
closeBtn.onclick = () => modal.style.display = "none";
window.onclick = (e) => { if (e.target === modal) modal.style.display = "none"; };

// Drag + drop //
const dropArea = document.getElementById("dropArea");
const imageInput = document.getElementById("imageInput");

dropArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropArea.style.background = "#c8d8e4";
});

dropArea.addEventListener("dragleave", () => {
    dropArea.style.background = "";
});

dropArea.addEventListener("drop", (e) => {
    e.preventDefault();
    dropArea.style.background = "";
    if (e.dataTransfer.files.length > 0) {
        imageInput.files = e.dataTransfer.files;
    }
});
