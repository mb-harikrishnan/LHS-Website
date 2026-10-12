/* =========================================================
   LITTLE HEARTS SCHOOL — GALLERY SCRIPTS
   Image popup with wheel-zoom + category filter (external)
   ========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    var popup = document.getElementById("imgPopup");
    var popupImg = document.getElementById("popupImg");
    var closeBtn = document.querySelector(".close-btn");
    var scale = 1;

    /* ---------- image click → open popup ---------- */
    document.querySelectorAll(".popup-image").forEach(function (img) {
        img.addEventListener("click", function () {
            popup.style.display = "flex";
            popupImg.src = this.getAttribute("data-img");
            scale = 1;
            popupImg.style.transform = "scale(1)";
        });
    });

    /* ---------- close button ---------- */
    if (closeBtn) {
        closeBtn.addEventListener("click", function () {
            popup.style.display = "none";
        });
    }

    /* ---------- close on backdrop click ---------- */
    popup.addEventListener("click", function (e) {
        if (e.target === popup) {
            popup.style.display = "none";
        }
    });

    /* ---------- mouse wheel zoom ---------- */
    popupImg.addEventListener("wheel", function (e) {
        e.preventDefault();
        if (e.deltaY < 0) {
            scale += 0.1;
        } else {
            scale -= 0.1;
            if (scale < 1) { scale = 1; }
        }
        popupImg.style.transform = "scale(" + scale + ")";
    });
});

/* ---------- category filter ---------- */
function filterGallery(category, button) {

    var items = document.querySelectorAll(".gallery-item");

    document.querySelectorAll(".filter-buttons button").forEach(function (btn) {
        btn.classList.remove("active");
    });

    button.classList.add("active");

    items.forEach(function (item) {
        if (category === "all" || item.classList.contains(category)) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
}
