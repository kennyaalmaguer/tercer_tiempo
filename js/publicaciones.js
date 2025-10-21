// Funcionalidad para los botones de like
document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', function() {
        this.classList.toggle('active');
        const countElement = this.querySelector('.action-count');
        let count = parseInt(countElement.textContent);
        
        if (this.classList.contains('active')) {
            countElement.textContent = count + 1;
        } else {
            countElement.textContent = count - 1;
        }
    });
});

// Funcionalidad para subir imagen
document.getElementById('image-upload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

function removeImage() {
    document.getElementById('image-preview').style.display = 'none';
    document.getElementById('image-upload').value = '';
}

// Funcionalidad para los botones de categorías
document.querySelectorAll('.categoria-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.categoria-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        this.classList.add('active');
        
        // Aquí iría la lógica para filtrar las publicaciones por categoría
        alert('Filtrando por categoría: ' + this.textContent);
    });
});

// Funcionalidad para el botón de publicar
document.querySelector('.publish-btn').addEventListener('click', function() {
    const textarea = document.querySelector('.create-post-input textarea');
    if (textarea.value.trim() === '') {
        alert('Por favor, escribe algo para publicar.');
        return;
    }
    
    alert('Publicación creada con éxito!');
    textarea.value = '';
    document.getElementById('image-preview').style.display = 'none';
});

// Funcionalidad para los filtros
document.querySelectorAll('.filtro-select').forEach(select => {
    select.addEventListener('change', function() {
        // Aquí iría la lógica para filtrar las publicaciones
        console.log('Filtrando por: ' + this.value);
    });
});