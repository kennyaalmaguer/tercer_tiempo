document.addEventListener("DOMContentLoaded", () => {
    // ====================
    // VARIABLES GLOBALES
    // ====================
    let selectedImages = []; // Array para almacenar todas las imágenes seleccionadas
    
    // ====================
    // 1. PREVISUALIZACIÓN DE MÚLTIPLES IMÁGENES (ACUMULATIVO)
    // ====================
    const imageUpload = document.getElementById("image-upload");
    const previewMultiple = document.getElementById("image-preview-multiple");

    if (imageUpload && previewMultiple) {
        // Función para actualizar la vista previa de imágenes
        function updateImagePreview() {
            previewMultiple.innerHTML = "";
            
            if (selectedImages.length === 0) {
                return;
            }
            
            selectedImages.forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function (e) {
                    // Crear contenedor de cada imagen
                    const box = document.createElement("div");
                    box.classList.add("preview-box");
                    
                    box.innerHTML = `
                        <img src="${e.target.result}" class="preview-img">
                        <button type="button" class="remove-image" data-index="${index}">×</button>
                    `;
                    
                    previewMultiple.appendChild(box);
                };
                
                reader.readAsDataURL(file);
            });
        }
        
        // Evento cuando se seleccionan nuevas imágenes
        imageUpload.addEventListener("change", function () {
            const files = Array.from(this.files);
            
            // Agregar los nuevos archivos al array existente
            files.forEach(file => {
                selectedImages.push(file);
            });
            
            // Actualizar la vista previa con todas las imágenes
            updateImagePreview();
            
            // Resetear el input para permitir seleccionar las mismas imágenes de nuevo
            this.value = "";
            
            // Verificar si se puede habilitar el botón de publicar
            checkPublishButton();
        });
        
        // Eliminar imagen específica
        previewMultiple.addEventListener("click", (e) => {
            if (e.target.classList.contains("remove-image")) {
                const index = parseInt(e.target.getAttribute("data-index"));
                
                // Eliminar la imagen del array
                selectedImages.splice(index, 1);
                
                // Actualizar la vista previa
                updateImagePreview();
                
                // Actualizar índices en los botones de eliminar
                document.querySelectorAll('.remove-image').forEach((btn, idx) => {
                    btn.setAttribute('data-index', idx);
                });
                
                // Verificar si se puede habilitar el botón de publicar
                checkPublishButton();
            }
        });
    }
    
    // ====================
    // 1.5. BOTÓN PARA LIMPIAR TODAS LAS IMÁGENES
    // ====================
    const clearImagesBtn = document.getElementById("clear-images-btn");
    if (clearImagesBtn) {
        clearImagesBtn.addEventListener("click", function() {
            selectedImages = [];
            if (previewMultiple) {
                previewMultiple.innerHTML = "";
            }
            checkPublishButton();
        });
    }

    // ====================
    // 2. MODAL DE SELECCIÓN (Mundial/Selección)
    // ====================
    const modal = document.getElementById("selection-modal");
    const closeBtn = document.querySelector(".close-btn");
    const modalTitle = document.getElementById("modal-title");
    const mundialOptions = document.getElementById("mundial-options");
    const seleccionOptions = document.getElementById("seleccion-options");
    
    // Variables para saber qué estamos seleccionando
    let currentTarget = ""; // "mundial" o "seleccion"
    
    // Botones que abren el modal
    document.querySelectorAll(".select-option-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            currentTarget = this.getAttribute("data-target");
            
            // Mostrar el título apropiado
            if (currentTarget === "mundial") {
                modalTitle.textContent = "Selecciona un Mundial";
                mundialOptions.style.display = "flex";
                seleccionOptions.style.display = "none";
            } else if (currentTarget === "seleccion") {
                modalTitle.textContent = "Selecciona una Selección";
                mundialOptions.style.display = "none";
                seleccionOptions.style.display = "flex";
            }
            
            // Mostrar el modal
            modal.style.display = "block";
        });
    });
    
    // Cerrar modal
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });
    
    // Cerrar modal al hacer clic fuera
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
    
    // Manejar selección de opciones en el modal
    document.querySelectorAll(".selection-item").forEach(item => {
        item.addEventListener("click", function() {
            const id = this.getAttribute("data-id");
            const name = this.getAttribute("data-name");
            
            // Actualizar el botón correspondiente
            if (currentTarget === "mundial") {
                document.getElementById("selected-mundial-id").value = id;
                document.getElementById("selected-mundial-name").textContent = name;
                
                // Marcar como activo
                document.querySelector('[data-target="mundial"]').classList.add("active");
            } else if (currentTarget === "seleccion") {
                document.getElementById("selected-seleccion-id").value = id;
                document.getElementById("selected-seleccion-name").textContent = name;
                
                // Marcar como activo
                document.querySelector('[data-target="seleccion"]').classList.add("active");
            }
            
            // Cerrar el modal
            modal.style.display = "none";
            
            // Verificar botón de publicar
            checkPublishButton();
        });
    });
    
    // ====================
    // 3. HABILITAR/DESHABILITAR BOTÓN PUBLICAR
    // ====================
    const publishBtn = document.querySelector(".publish-btn");
    const postTextarea = document.getElementById("post-textarea");
    
    if (publishBtn && postTextarea) {
        function checkPublishButton() {
            const hasText = postTextarea.value.trim().length > 0;
            const hasImages = selectedImages.length > 0; // Usar el array de imágenes
            const hasVideo = document.getElementById("video-upload")?.files.length > 0;
            const hasMundial = document.getElementById("selected-mundial-id").value !== "";
            const hasSeleccion = document.getElementById("selected-seleccion-id").value !== "";
            
            // Habilitar si hay texto, imágenes o video
            // También verificar si al menos un mundial está seleccionado (opcional, según tus reglas)
            publishBtn.disabled = !(hasText || hasImages || hasVideo);
        }
        
        postTextarea.addEventListener("input", checkPublishButton);
        
        // Video upload listener
        document.getElementById("video-upload")?.addEventListener("change", function() {
            checkPublishButton();
            
            // Opcional: previsualización de video
            if (this.files.length > 0) {
                const videoPreview = document.getElementById("video-preview");
                videoPreview.innerHTML = "";
                
                const file = this.files[0];
                const video = document.createElement("video");
                video.src = URL.createObjectURL(file);
                video.controls = true;
                video.style.maxWidth = "100%";
                video.style.maxHeight = "200px";
                
                const removeBtn = document.createElement("button");
                removeBtn.className = "remove-image";
                removeBtn.innerHTML = "×";
                removeBtn.style.position = "absolute";
                removeBtn.style.top = "5px";
                removeBtn.style.right = "5px";
                removeBtn.addEventListener("click", function() {
                    videoPreview.innerHTML = "";
                    document.getElementById("video-upload").value = "";
                    checkPublishButton();
                });
                
                const videoContainer = document.createElement("div");
                videoContainer.style.position = "relative";
                videoContainer.style.display = "inline-block";
                videoContainer.appendChild(video);
                videoContainer.appendChild(removeBtn);
                
                videoPreview.appendChild(videoContainer);
            }
        });
        
        // Verificar al cargar
        checkPublishButton();
    }
    
    // ====================
    // 4. FUNCIONALIDAD DE LIKES
    // ====================
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
    
    // ====================
    // 5. FILTRADO POR CATEGORÍAS
    // ====================
    document.querySelectorAll('.categoria-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.categoria-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            const categoriaId = this.getAttribute('data-categoria-id');
            console.log('Filtrando por categoría ID:', categoriaId);
            
            // Aquí iría la lógica AJAX para filtrar las publicaciones
            // fetch(`filtrar_publicaciones.php?categoria_id=${categoriaId}`)
            //   .then(response => response.text())
            //   .then(html => {
            //       document.querySelector('.publicaciones-sec').innerHTML = html;
            //   });
        });
    });
    
    // ====================
    // 6. SUBIR VIDEO (manejado en sección 3)
    // ====================
    // Ya se maneja en la sección 3 (checkPublishButton)
    
    // ====================
    // 7. ENVIAR PUBLICACIÓN
    // ====================
    if (publishBtn) {
        publishBtn.addEventListener("click", function() {
            if (this.disabled) return;
            
            const text = postTextarea.value.trim();
            const mundialId = document.getElementById("selected-mundial-id").value;
            const seleccionId = document.getElementById("selected-seleccion-id").value;
            const videoFiles = document.getElementById("video-upload")?.files;
            
            console.log("Publicando:", {
                text,
                mundialId,
                seleccionId,
                images: selectedImages.length,
                videos: videoFiles?.length || 0
            });
            
            // Aquí iría la lógica AJAX para enviar los datos al servidor
            /*
            const formData = new FormData();
            formData.append("texto", text);
            formData.append("id_mundial", mundialId);
            formData.append("id_seleccion", seleccionId);
            
            // Agregar imágenes desde el array
            selectedImages.forEach((image, index) => {
                formData.append("imagenes[]", image);
            });
            
            if (videoFiles) {
                for (let i = 0; i < videoFiles.length; i++) {
                    formData.append("videos[]", videoFiles[i]);
                }
            }
            
            fetch("crear_publicacion.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Publicación creada exitosamente");
                    
                    // Limpiar formulario
                    postTextarea.value = "";
                    selectedImages = [];
                    if (previewMultiple) {
                        previewMultiple.innerHTML = "";
                    }
                    
                    // Limpiar video
                    const videoPreview = document.getElementById("video-preview");
                    if (videoPreview) {
                        videoPreview.innerHTML = "";
                    }
                    document.getElementById("video-upload").value = "";
                    
                    // Limpiar selecciones
                    document.getElementById("selected-mundial-name").textContent = "mundial";
                    document.getElementById("selected-seleccion-name").textContent = "selección";
                    document.getElementById("selected-mundial-id").value = "";
                    document.getElementById("selected-seleccion-id").value = "";
                    
                    document.querySelectorAll('.select-option-btn').forEach(btn => {
                        btn.classList.remove("active");
                    });
                    
                    checkPublishButton();
                    
                    // Recargar publicaciones
                    // location.reload(); // O cargar nuevas publicaciones via AJAX
                }
            })
            .catch(error => {
                console.error("Error al publicar:", error);
                alert("Error al crear la publicación");
            });
            */
        });
    }
    
    // ====================
    // 8. FUNCIONALIDAD DE BÚSQUEDA
    // ====================
    const searchInput = document.querySelector('.search-bar input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm.length > 0) {
                    console.log("Buscando:", searchTerm);
                    // Aquí iría la lógica AJAX para buscar publicaciones
                }
            }
        });
    }
});

// Agrega esta sección al final del archivo (antes del cierre de DOMContentLoaded)

// ====================
// 9. FILTRADO DINÁMICO DE PUBLICACIONES
// ====================
const filtroMundial = document.getElementById("filtro-mundial");
const filtroSeleccion = document.getElementById("filtro-seleccion");
const filtroOrden = document.getElementById("filtro-orden");
const aplicarFiltrosBtn = document.getElementById("aplicar-filtros");
const limpiarFiltrosBtn = document.getElementById("limpiar-filtros");

// Función para aplicar filtros
function aplicarFiltros() {
    const mundialId = filtroMundial.value;
    const seleccionId = filtroSeleccion.value;
    const orden = filtroOrden.value;
    
    console.log("Aplicando filtros:", {
        mundialId,
        seleccionId,
        orden
    });
    
    // Aquí iría la lógica AJAX para filtrar publicaciones
    /*
    fetch(`filtrar_publicaciones.php?mundial_id=${mundialId}&seleccion_id=${seleccionId}&orden=${orden}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar las publicaciones mostradas
                actualizarPublicaciones(data.publicaciones);
            }
        })
        .catch(error => {
            console.error("Error al filtrar:", error);
        });
    */
}

// Función para limpiar filtros
function limpiarFiltros() {
    filtroMundial.value = "0";
    filtroSeleccion.value = "0";
    filtroOrden.value = "reciente";
    
    // Aplicar filtros (que mostrará todos)
    aplicarFiltros();
}

// Event listeners para filtros
if (aplicarFiltrosBtn) {
    aplicarFiltrosBtn.addEventListener("click", aplicarFiltros);
}

if (limpiarFiltrosBtn) {
    limpiarFiltrosBtn.addEventListener("click", limpiarFiltros);
}

// También puedes aplicar filtros automáticamente al cambiar los selects
if (filtroMundial && filtroSeleccion && filtroOrden) {
    filtroMundial.addEventListener("change", aplicarFiltros);
    filtroSeleccion.addEventListener("change", aplicarFiltros);
    filtroOrden.addEventListener("change", aplicarFiltros);
}

// Función para actualizar publicaciones (ejemplo)
function actualizarPublicaciones(publicaciones) {
    const publicacionesSec = document.querySelector('.publicaciones-sec');
    
    // Remover las publicaciones actuales (excepto los filtros)
    const posts = document.querySelectorAll('.post-carta');
    posts.forEach(post => post.remove());
    
    // Agregar las nuevas publicaciones
    // Esto es solo un ejemplo - deberías adaptarlo a tu estructura
    if (publicaciones.length === 0) {
        const noResults = document.createElement('div');
        noResults.className = 'no-results';
        noResults.innerHTML = '<p>No se encontraron publicaciones con estos filtros.</p>';
        publicacionesSec.appendChild(noResults);
    } else {
        publicaciones.forEach(publicacion => {
            const post = crearPostElemento(publicacion);
            publicacionesSec.appendChild(post);
        });
    }
}

// Función para crear elementos de post (ejemplo)
function crearPostElemento(publicacion) {
    const post = document.createElement('div');
    post.className = 'post-carta';
    // Aquí construirías el HTML del post según los datos recibidos
    return post;
}