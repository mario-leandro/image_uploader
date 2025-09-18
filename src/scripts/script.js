const btnSalvar = document.getElementById("btnSalvar");
const imagemCarregada = document.getElementById("imagem-carregada");
const inputImagem = document.getElementById("imagem");

let imagemSelecionada = null;

btnSalvar.style.display = "none";

inputImagem.addEventListener("change", () => {
    if (inputImagem.files && inputImagem.files[0]) {
        imagemSelecionada = inputImagem.files[0];

        const reader = new FileReader();
        reader.onload = function (e) {
            imagemCarregada.innerHTML = `
                <img src="${e.target.result}" alt="Imagem carregada" style="width: 300px; height: 300px;" />
            `;
        };
        reader.readAsDataURL(imagemSelecionada);
        btnSalvar.style.display = "block";
    }
});

btnSalvar.addEventListener("click", async () => {
    if (!imagemSelecionada) {
        alert("Nenhuma imagem selecionada para salvar.");
        return;
    }

    const formData = new FormData();
    formData.append("imagem", imagemSelecionada);

    try {
        const response = await fetch("./src/services/api/upload_image.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            imagemCarregada.innerHTML = `<p style="color:green;">Imagem salva com sucesso!</p>`;
            btnSalvar.style.display = "none";
            imagemSelecionada = null;
        } else {
            imagemCarregada.innerHTML = `<p style="color:red;">Erro ao salvar a imagem.</p>`;
        }
    } catch (error) {
        imagemCarregada.innerHTML = `<p style="color:red;">Falha na requisição.</p>`;
    }
});
