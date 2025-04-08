document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".copy-btn").forEach(button => {
        button.addEventListener("click", function (event) {
            event.stopPropagation();

            const promoCode = this.previousElementSibling.textContent.trim();

            if (promoCode) {
                navigator.clipboard.writeText(promoCode).then(() => {
                    this.textContent = "Skopiowano!";
                    setTimeout(() => {
                        this.textContent = "Kopiuj";
                    }, 2000);
                }).catch(err => {
                    console.error("Błąd kopiowania: ", err);
                });
            }
        });
    });
});
