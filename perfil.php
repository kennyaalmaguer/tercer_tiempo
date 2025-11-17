<?php
session_start();

// Redirigir si no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener datos del usuario desde la sesión
$user_id = $_SESSION['user_id'];
$user_nombre = $_SESSION['user_nombre'];
$user_apellido_paterno = $_SESSION['user_apellido_paterno'];
$user_apellido_materno = $_SESSION['user_apellido_materno'];
$user_email = $_SESSION['user_email'];
$user_fecha_nacimiento = $_SESSION['user_nombre'];
$user_genero = $_SESSION['user_genero'];
$user_pais_nacimiento = $_SESSION['user_pais_nacimiento'];
$user_nacionalidad = $_SESSION['user_nacionalidad'];
$user_foto = $_SESSION['user_foto'] ?? 'img/profile2.jpg'; // Foto por defecto
$user_descripcion = $_SESSION['user_descripcion'] ?? 'Apasionado por el fútbol';

// Nombre completo
$nombre_completo = $user_nombre . ' ' . $user_apellido_paterno;
if (!empty($user_apellido_materno)) {
    $nombre_completo .= ' ' . $user_apellido_materno;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/LOGO.png" type="image/x-icon">
    <title>tercer tiempo - perfil</title>
    <link rel="stylesheet" href="css/styles.css"> 
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- portada -->
<div class="fportada">
    <header>
        <div class="nav-item">
            <?php echo "<p>Tt</p>"; ?>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="buscar...">
        </div>

        <nav>
            <a href="publicaciones.php">publicaciones</a>
            <a href="registro.php">registro</a>
            <a href="perfil.php">perfil</a>
            <a href="logout.php">cerrar sesión</a>
        </nav>
    </header>

    <!-- foto de perfil con nombre dentro de la portada -->
    <div class="perfil-container">
        <div class="perfil-foto" id="profile-picture">
            <img src="<?php echo htmlspecialchars($user_foto); ?>" alt="foto de perfil">
            <div class="edit-overlay">
                <i class="fas fa-camera"></i>
            </div>
        </div>
        <div class="perfil-info">
            <span class="perfil-nombre" id="profile-name"><?php echo htmlspecialchars($nombre_completo); ?></span>
            <p class="perfil-descripcion" id="profile-description"><?php echo htmlspecialchars($user_descripcion); ?></p> 
            <ul class="perfil-datos">
                <li><?php echo date('d/m/Y', strtotime($user_fecha_nacimiento)); ?></li>
                <li><?php echo htmlspecialchars($user_email); ?></li>
                <li><?php echo htmlspecialchars($user_genero); ?></li>
            </ul>
        </div>
        <button class="edit-profile-btn" id="edit-profile-btn">editar perfil</button>
    </div>
</div>

<!-- segunda sección - ahora con formulario de edición -->
<section class="segunda-seccion">
    <div class="edit-profile-form" id="edit-profile-form" style="display: none;">
        <h2>editar perfil</h2>
        <form id="profile-form" action="actualizar_perfil.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="full-name">nombre completo</label>
                <input type="text" id="full-name" name="full-name" value="<?php echo htmlspecialchars($nombre_completo); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="birthdate">fecha de nacimiento</label>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo $user_fecha_nacimiento; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="gender">género</label>
                <select id="gender" name="gender" required>
                    <option value="femenino" <?php echo $user_genero == 'femenino' ? 'selected' : ''; ?>>femenino</option>
                    <option value="masculino" <?php echo $user_genero == 'masculino' ? 'selected' : ''; ?>>masculino</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="country">país de nacimiento</label>
                <select id="country" name="country" required>
                    <option value="mx" <?php echo $user_pais_nacimiento == 'mx' ? 'selected' : ''; ?>>méxico</option>
                    <option value="ar" <?php echo $user_pais_nacimiento == 'ar' ? 'selected' : ''; ?>>argentina</option>
                    <option value="es" <?php echo $user_pais_nacimiento == 'es' ? 'selected' : ''; ?>>españa</option>
                    <option value="co" <?php echo $user_pais_nacimiento == 'co' ? 'selected' : ''; ?>>colombia</option>
                    <option value="br" <?php echo $user_pais_nacimiento == 'br' ? 'selected' : ''; ?>>brasil</option>
                    <option value="us" <?php echo $user_pais_nacimiento == 'us' ? 'selected' : ''; ?>>estados unidos</option>
                    <option value="fr" <?php echo $user_pais_nacimiento == 'fr' ? 'selected' : ''; ?>>francia</option>
                    <option value="de" <?php echo $user_pais_nacimiento == 'de' ? 'selected' : ''; ?>>alemania</option>
                    <option value="it" <?php echo $user_pais_nacimiento == 'it' ? 'selected' : ''; ?>>italia</option>
                    <option value="uk" <?php echo $user_pais_nacimiento == 'uk' ? 'selected' : ''; ?>>reino unido</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="nationality">nacionalidad</label>
                <select id="nationality" name="nationality" required>
                    <option value="mx" <?php echo $user_nacionalidad == 'mx' ? 'selected' : ''; ?>>mexicana</option>
                    <option value="ar" <?php echo $user_nacionalidad == 'ar' ? 'selected' : ''; ?>>argentina</option>
                    <option value="es" <?php echo $user_nacionalidad == 'es' ? 'selected' : ''; ?>>española</option>
                    <option value="co" <?php echo $user_nacionalidad == 'co' ? 'selected' : ''; ?>>colombiana</option>
                    <option value="br" <?php echo $user_nacionalidad == 'br' ? 'selected' : ''; ?>>brasileña</option>
                    <option value="us" <?php echo $user_nacionalidad == 'us' ? 'selected' : ''; ?>>estadounidense</option>
                    <option value="fr" <?php echo $user_nacionalidad == 'fr' ? 'selected' : ''; ?>>francesa</option>
                    <option value="de" <?php echo $user_nacionalidad == 'de' ? 'selected' : ''; ?>>alemana</option>
                    <option value="it" <?php echo $user_nacionalidad == 'it' ? 'selected' : ''; ?>>italiana</option>
                    <option value="uk" <?php echo $user_nacionalidad == 'uk' ? 'selected' : ''; ?>>británica</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="email">correo electrónico</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">descripción</label>
                <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($user_descripcion); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="profile-picture">foto de perfil</label>
                <input type="file" id="profile-picture" name="profile_picture" accept="image/*">
                <small>Formatos: JPG, PNG, GIF (Máx. 5MB)</small>
            </div>
            
            <div class="form-group">
                <label for="current-password">contraseña actual</label>
                <input type="password" id="current-password" name="current-password">
            </div>
            
            <div class="form-group">
                <label for="new-password">nueva contraseña</label>
                <input type="password" id="new-password" name="new-password">
                <small>mínimo 8 caracteres, debe incluir mayúsculas, minúsculas, números y símbolos</small>
            </div>
            
            <div class="form-group">
                <label for="confirm-password">confirmar nueva contraseña</label>
                <input type="password" id="confirm-password" name="confirm-password">
            </div>
            
            <div class="form-actions">
                <button type="button" id="cancel-edit">cancelar</button>
                <button type="submit">guardar cambios</button>
            </div>
        </form>
    </div>
</section>

<!-- Resto del código se mantiene igual -->
<section class="user-posts-section">
    <h2>mis publicaciones</h2>
    
    <div class="posts-filter">
        <button class="filter-btn active" data-filter="all">todas</button>
        <button class="filter-btn" data-filter="popular">más populares</button>
        <button class="filter-btn" data-filter="recent">más recientes</button>
    </div>
    
    <div class="user-posts" id="user-posts">
        <!-- Las publicaciones se cargarán aquí dinámicamente -->
    </div>
</section>

<!-- El resto del código HTML y JavaScript se mantiene igual -->
<!-- ... -->

<script>
    // Tu código JavaScript existente se mantiene igual
    // Solo actualiza la parte del formulario para usar AJAX

    document.getElementById('profile-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('actualizar_perfil.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar la información en la interfaz
                document.getElementById('profile-name').textContent = document.getElementById('full-name').value;
                document.getElementById('profile-description').textContent = document.getElementById('description').value;
                
                // Actualizar foto si se cambió
                if (data.new_photo) {
                    document.querySelector('.perfil-foto img').src = data.new_photo;
                }
                
                // Ocultar el formulario
                document.getElementById('edit-profile-form').style.display = 'none';
                
                alert('perfil actualizado correctamente');
                
                // Recargar la página para ver todos los cambios
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar el perfil');
        });
    });

    // El resto de tu JavaScript se mantiene igual...
</script>
</body>
</html>