// get the buttons and modal elements
const openModal = document.getElementById("openModal");
const closeModal = document.getElementById("closeModal");
const modal = document.getElementById("profileModal");
const dropArea = document.getElementById("dropArea");
const fileInput = document.getElementById("fileInput");

// open the modal when you click button
if (openModal) {
    openModal.onclick = () => {
        modal.style.display = "flex"; // show the modal
    };
}

// close the modal when you click the close button
if (closeModal) {
    closeModal.onclick = () => {
        modal.style.display = "none"; 
    };
}

// close the modal 
window.onclick = (e) => {
    if (e.target === modal) {
        modal.style.display = "none"; 
    }
};

// drag and drop / file upload area
if (dropArea) {
    dropArea.onclick = () => fileInput.click(); // click the area = open file picker

    dropArea.addEventListener("dragover", (e) => {
        e.preventDefault(); // prevent default so drop works
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
