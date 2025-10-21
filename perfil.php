<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/LOGO.png" type="image/x-icon">
    <title>Tercer Tiempo - Perfil</title>
    <link rel="stylesheet" href="css/styles.css"> 
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <!-- portada -->
<div class="fportada">
    <header>
        <div class="nav-item">
            <?php echo "<p>5t</p>"; ?>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="buscar...">
        </div>

        <nav>
            <a href="publicaciones.php">publicaciones</a>
            <a href="registro.php">registro</a>
            <a href="perfil.php">perfil</a>
        </nav>
    </header>

    <!-- foto de perfil con nombre dentro de la portada -->
    <div class="perfil-container">
        <div class="perfil-foto" id="profile-picture">
            <img src="img/profile2.jpg" alt="foto de perfil">
            <div class="edit-overlay">
                <i class="fas fa-camera"></i>
            </div>
        </div>
        <div class="perfil-info">
            <span class="perfil-nombre" id="profile-name">naiela dev</span>
            <p class="perfil-descripcion" id="profile-description">apasionada por el fútbol</p> 
            <ul class="perfil-datos">
                <li>12/03/2000</li>
                <li>naiela@gmail.com</li>
                <li>femenino</li>
            </ul>
        </div>
        <button class="edit-profile-btn" id="edit-profile-btn">Editar perfil</button>
    </div>
</div>

<!-- segunda sección - ahora con formulario de edición -->
<section class="segunda-seccion">
    <div class="edit-profile-form" id="edit-profile-form" style="display: none;">
        <h2>Editar perfil</h2>
        <form id="profile-form">
            <div class="form-group">
                <label for="full-name">Nombre completo</label>
                <input type="text" id="full-name" name="full-name" value="Naiela Dev" required>
            </div>
            
            <div class="form-group">
                <label for="birthdate">Fecha de nacimiento</label>
                <input type="date" id="birthdate" name="birthdate" value="2000-03-12" required>
            </div>
            
            <div class="form-group">
                <label for="gender">Género</label>
                <select id="gender" name="gender" required>
                    <option value="femenino" selected>Femenino</option>
                    <option value="masculino">Masculino</option>
                    <option value="otro">Otro</option>
                    <option value="prefiero-no-decir">Prefiero no decir</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="country">País de nacimiento</label>
                <input type="text" id="country" name="country" value="México" required>
            </div>
            
            <div class="form-group">
                <label for="nationality">Nacionalidad</label>
                <input type="text" id="nationality" name="nationality" value="Mexicana" required>
            </div>
            
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" value="naiela@gmail.com" required>
            </div>
            
            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea id="description" name="description" rows="3">apasionada por el fútbol</textarea>
            </div>
            
            <div class="form-group">
                <label for="current-password">Contraseña actual</label>
                <input type="password" id="current-password" name="current-password">
            </div>
            
            <div class="form-group">
                <label for="new-password">Nueva contraseña</label>
                <input type="password" id="new-password" name="new-password">
                <small>Mínimo 8 caracteres, debe incluir mayúsculas, minúsculas, números y símbolos</small>
            </div>
            
            <div class="form-group">
                <label for="confirm-password">Confirmar nueva contraseña</label>
                <input type="password" id="confirm-password" name="confirm-password">
            </div>
            
            <div class="form-actions">
                <button type="button" id="cancel-edit">Cancelar</button>
                <button type="submit">Guardar cambios</button>
            </div>
        </form>
    </div>
</section>

<!-- sección para crear nueva publicación -->
<section class="create-post-sec">
    <div class="create-post-container">
        <div class="create-post-box">
            <div class="create-post-header">
                <div class="create-post-avatar">
                    <img src="img/profile2.jpg" alt="avatar">
                </div>
                <div class="create-post-input">
                    <textarea placeholder="¿Qué quieres compartir hoy?" id="post-text"></textarea>
                </div>
            </div>
            <div class="create-post-options">
                <div class="post-options">
                    <label for="image-upload" class="option-btn">
                        <i class="fas fa-image option-icon"></i>
                        <span>Imagen</span>
                    </label>
                    <input type="file" id="image-upload" accept="image/*" style="display: none;">
                </div>
                <button class="publish-btn" id="publish-post">Publicar</button>
            </div>
            <div class="image-preview" id="image-preview">
                <img id="preview-img" src="" alt="Vista previa">
                <button class="remove-image" onclick="removeImage()">×</button>
            </div>
        </div>
    </div>
</section>

<!-- sección de publicaciones del usuario -->
<section class="user-posts-section">
    <h2>Mis publicaciones</h2>
    
    <div class="posts-filter">
        <button class="filter-btn active" data-filter="all">Todas</button>
        <button class="filter-btn" data-filter="popular">Más populares</button>
        <button class="filter-btn" data-filter="recent">Más recientes</button>
    </div>
    
    <div class="user-posts" id="user-posts">
        <!-- Las publicaciones se cargarán aquí dinámicamente -->
    </div>
</section>

<!-- sección de publicaciones con post tipo carta -->
<section class="post-sec">
    <div class="post-carta">
        <div class="post-header">
            <div class="post-avatar">
                <img src="img/profile2.jpg" alt="avatar">
            </div>
            <div class="post-author">naiela dev</div>
            <div class="post-time">hace 3 horas</div>
            <div class="post-views">
                <i class="fas fa-eye"></i>
                <span>1.2k</span>
            </div>
        </div>
        
        <div class="post-content">
            <div class="post-image">
                <img src="img/back.jpg" alt="mundial 2026">
            </div>
            
            <div class="post-text">
                <h2 class="post-title">mundial 2026: expectativas y novedades</h2>
                <p class="post-description">
                    la copa mundial de la fifa 2026 se realizará en estados unidos, canadá y méxico, 
                    siendo el primer torneo en tener tres países anfitriones. con 48 equipos participantes 
                    (16 más que en ediciones anteriores), promete ser el mundial más grande de la historia.
                    <br><br>
                    ¿qué opinan sobre esta expansión? ¿creen que beneficiará al fútbol global o diluirá 
                    la calidad del torneo? me encantaría conocer sus opiniones sobre este histórico evento 
                    y sus expectativas para la selección de nuestro país.
                </p>
            </div>
        </div>
        
        <div class="post-actions">
            <div class="post-action like-btn" id="like-button">
                <i class="action-icon fas fa-heart"></i>
                <span class="action-count">243</span>
            </div>
            <div class="post-action comment-btn" id="comment-button">
                <i class="action-icon fas fa-comment"></i>
                <span class="action-count">47</span>
            </div>
            <div class="post-action view-btn">
                <i class="action-icon fas fa-eye"></i>
                <span class="action-count">1.2k</span>
            </div>
        </div>
        
        <!-- Sección de comentarios -->
        <div class="comments-section" id="comments-section" style="display: none;">
            <div class="comments-header">
                <h3>Comentarios (47)</h3>
            </div>
            
            <div class="comments-list">
                <!-- Comentarios se cargarán aquí -->
                <div class="comment">
                    <div class="comment-avatar">
                        <img src="img/user1.jpg" alt="Usuario">
                    </div>
                    <div class="comment-content">
                        <div class="comment-author">futbol_fan23</div>
                        <div class="comment-text">Estoy emocionado por el mundial 2026, aunque me preocupa que con tantos equipos pierda calidad el torneo.</div>
                        <div class="comment-time">hace 2 horas</div>
                    </div>
                </div>
                
                <div class="comment">
                    <div class="comment-avatar">
                        <img src="img/user2.jpg" alt="Usuario">
                    </div>
                    <div class="comment-content">
                        <div class="comment-author">soccer_lover</div>
                        <div class="comment-text">¡Excelente publicación! Creo que la expansión dará oportunidad a más países de participar.</div>
                        <div class="comment-time">hace 1 hora</div>
                    </div>
                </div>
            </div>
            
            <div class="add-comment">
                <div class="comment-avatar">
                    <img src="img/profile2.jpg" alt="Tu avatar">
                </div>
                <div class="comment-input">
                    <input type="text" placeholder="Escribe un comentario...">
                    <button class="send-comment"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Datos de ejemplo para las publicaciones del usuario
    const userPosts = [
        {
            id: 1,
            title: "mundial 2026: expectativas y novedades",
            content: "la copa mundial de la fifa 2026 se realizará en estados unidos, canadá y méxico, siendo el primer torneo en tener tres países anfitriones...",
            image: "img/back.jpg",
            likes: 243,
            comments: 47,
            views: 1200,
            date: "hace 3 horas",
            popular: true
        },
        {
            id: 2,
            title: "mi equipo favorito gana la liga",
            content: "Qué emoción ver a mi equipo levantar el trofeo después de tantos años...",
            image: "img/team.jpg",
            likes: 89,
            comments: 12,
            views: 450,
            date: "hace 2 días",
            popular: false
        },
        {
            id: 3,
            title: "análisis táctico del último partido",
            content: "El entrenador implementó una formación 4-3-3 que funcionó perfectamente...",
            image: "img/tactics.jpg",
            likes: 156,
            comments: 23,
            views: 780,
            date: "hace 1 semana",
            popular: true
        }
    ];

    // Funcionalidad para el botón de editar perfil
    document.getElementById('edit-profile-btn').addEventListener('click', function() {
        const form = document.getElementById('edit-profile-form');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });

    document.getElementById('cancel-edit').addEventListener('click', function() {
        document.getElementById('edit-profile-form').style.display = 'none';
    });

    // Funcionalidad para el formulario de perfil
    document.getElementById('profile-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Aquí iría la lógica para guardar los cambios en la base de datos
        const fullName = document.getElementById('full-name').value;
        const description = document.getElementById('description').value;
        
        // Actualizar la información en la interfaz
        document.getElementById('profile-name').textContent = fullName;
        document.getElementById('profile-description').textContent = description;
        
        // Ocultar el formulario
        document.getElementById('edit-profile-form').style.display = 'none';
        
        alert('Perfil actualizado correctamente');
    });

    // Funcionalidad para el botón de like
    document.getElementById('like-button').addEventListener('click', function() {
        this.classList.toggle('active');
        const countElement = this.querySelector('.action-count');
        let count = parseInt(countElement.textContent);
        
        if (this.classList.contains('active')) {
            countElement.textContent = count + 1;
        } else {
            countElement.textContent = count - 1;
        }
    });

    // Funcionalidad para mostrar/ocultar comentarios
    document.getElementById('comment-button').addEventListener('click', function() {
        const commentsSection = document.getElementById('comments-section');
        commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
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

    // Funcionalidad para publicar nueva publicación
    document.getElementById('publish-post').addEventListener('click', function() {
        const postText = document.getElementById('post-text').value;
        if (postText.trim() !== '') {
            // Aquí iría la lógica para guardar la publicación en la base de datos
            alert('Publicación creada correctamente');
            document.getElementById('post-text').value = '';
            document.getElementById('image-preview').style.display = 'none';
            document.getElementById('image-upload').value = '';
            
            // Recargar las publicaciones del usuario
            loadUserPosts();
        } else {
            alert('Por favor, escribe algo para publicar');
        }
    });

    // Cargar publicaciones del usuario
    function loadUserPosts(filter = 'all') {
        const postsContainer = document.getElementById('user-posts');
        postsContainer.innerHTML = '';
        
        let filteredPosts = userPosts;
        
        if (filter === 'popular') {
            filteredPosts = userPosts.filter(post => post.popular);
        } else if (filter === 'recent') {
            // En una implementación real, esto se ordenaría por fecha
            filteredPosts = [...userPosts].reverse();
        }
        
        filteredPosts.forEach(post => {
            const postElement = document.createElement('div');
            postElement.className = 'user-post-card';
            postElement.innerHTML = `
                <div class="user-post-header">
                    <div class="user-post-image">
                        <img src="${post.image}" alt="${post.title}">
                    </div>
                    <div class="user-post-info">
                        <h3>${post.title}</h3>
                        <p>${post.content.substring(0, 100)}...</p>
                        <div class="user-post-stats">
                            <span><i class="fas fa-heart"></i> ${post.likes}</span>
                            <span><i class="fas fa-comment"></i> ${post.comments}</span>
                            <span><i class="fas fa-eye"></i> ${post.views}</span>
                        </div>
                        <div class="user-post-date">${post.date}</div>
                    </div>
                </div>
            `;
            postsContainer.appendChild(postElement);
        });
    }

    // Filtros para las publicaciones
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            loadUserPosts(this.getAttribute('data-filter'));
        });
    });

    // Inicializar cargando todas las publicaciones
    loadUserPosts();
</script>

</body>
</html>