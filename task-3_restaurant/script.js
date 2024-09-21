const modalBtn =document.getElementById("modal_ac");
const modal =document.querySelector(".modal");
const modalKapat = document.getElementById("modal-kapat");

const modal1 =document.querySelector(".modal1");
const modalBtn1 = document.getElementById("modal_ac1");
const modalKapat1 =document.getElementById("modal-kapat1")

modalBtn.addEventListener("click", () =>{
    modal.style.display ="flex";
});

modalKapat.addEventListener("click", () =>{
    modal.style.display ="none";
});

modalBtn1.addEventListener("click", () =>{
    modal1.style.display ="flex";
}); 

modalKapat1.addEventListener("click", () =>{
    modal1.style.display ="none";
});

document.addEventListener('DOMContentLoaded', () => {
    const dropdown = document.querySelector('.dropdown');
    const submenu = document.querySelector('.submenu');

    dropdown.addEventListener('click', (event) => {
        event.preventDefault();
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
    });
});
 

document.addEventListener("DOMContentLoaded", function () {
    // Tüm dropdown menülerini seç
    const dropdowns = document.querySelectorAll('.dropdown');
    
    // Her dropdown için tıklama olayını ekle
    dropdowns.forEach(function (dropdown) {
        dropdown.addEventListener('click', function (e) {
            e.preventDefault(); // Linkin varsayılan davranışını engelle
            
            // Alt menüyü bul ve aç/kapa
            const submenu = this.nextElementSibling;
            if (submenu.style.display === "block") {
                submenu.style.display = "none";
            } else {
                submenu.style.display = "block";
            }
        });
    });
});

 
 
     