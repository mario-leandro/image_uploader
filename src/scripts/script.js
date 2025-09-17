const form = document.getElementById("formConfig");
const lista_imagens = document.getElementById("image-list");

form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const fileInput = document.getElementById("imagem");
    const formData = new FormData();
    formData.append("imagem", fileInput.files[0]);

    try {
        const response = await fetch("./src/services/api/upload_image.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            lista_imagens.innerHTML += `
                <p>Upload realizado!</p>
                <img src="${result.url}" alt="Imagem enviada">
            `;
        } else {
            lista_imagens.innerHTML = `<p style="color:red;">Erro: ${result.error}</p>`;
        }
    } catch (error) {
        lista_imagens.innerHTML = `<p style="color:red;">Falha na requisição.</p>`;
    }
});