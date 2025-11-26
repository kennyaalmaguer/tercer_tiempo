<?php
session_start();

// DEBUG: Mostrar datos de sesión
echo "<!-- DEBUG SESIÓN: ";
print_r($_SESSION);
echo " -->";

// Redirigir si no está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// ✅ CORREGIDO: Si no existen las sesiones separadas, crearlas desde nombre_completo
if (!isset($_SESSION['user_nombre']) && isset($_SESSION['user_nombre_completo'])) {
    // Dividir nombre_completo en partes
    $nombre_completo = $_SESSION['user_nombre_completo'];
    $partes = explode(' ', $nombre_completo);
    
    $_SESSION['user_nombre'] = $partes[0] ?? '';
    $_SESSION['user_apellido_paterno'] = $partes[1] ?? '';
    $_SESSION['user_apellido_materno'] = $partes[2] ?? '';
}

// Obtener datos del usuario desde la sesión
$user_id = $_SESSION['user_id'];
$user_nombre = $_SESSION['user_nombre'] ?? '';
$user_apellido_paterno = $_SESSION['user_apellido_paterno'] ?? '';
$user_apellido_materno = $_SESSION['user_apellido_materno'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
$user_fecha_nacimiento = $_SESSION['user_fecha_nacimiento'] ?? '';
$user_genero = $_SESSION['user_genero'] ?? '';
$user_pais_nacimiento = $_SESSION['user_pais_nacimiento'] ?? '';
$user_nacionalidad = $_SESSION['user_nacionalidad'] ?? '';
$user_foto = $_SESSION['user_foto'] ?? 'img/profile2.jpg';
$user_descripcion = $_SESSION['user_descripcion'] ?? 'Apasionado por el fútbol';

// DEBUG: Mostrar datos procesados
echo "<!-- DEBUG DATOS PROCESADOS: ";
echo "user_id: $user_id, ";
echo "user_nombre: $user_nombre, ";
echo "user_apellido_paterno: $user_apellido_paterno, ";
echo "user_apellido_materno: $user_apellido_materno, ";
echo "user_email: $user_email";
echo " -->";

// Nombre completo
$nombre_completo = $user_nombre;
if (!empty($user_apellido_paterno)) {
    $nombre_completo .= ' ' . $user_apellido_paterno;
}
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
            <!-- Campos SEPARADOS para nombre y apellidos -->
            <div class="form-group">
                <label for="nombre">nombre *</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user_nombre); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="apellido_paterno">apellido paterno</label>
                <input type="text" id="apellido_paterno" name="apellido_paterno" value="<?php echo htmlspecialchars($user_apellido_paterno); ?>">
            </div>
            
            <div class="form-group">
                <label for="apellido_materno">apellido materno</label>
                <input type="text" id="apellido_materno" name="apellido_materno" value="<?php echo htmlspecialchars($user_apellido_materno); ?>">
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

<!-- Sección de publicaciones -->
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

<script>
// DEBUG: Verificar que los elementos existen
console.log('DEBUG: Iniciando JavaScript...');
console.log('Edit button:', document.getElementById('edit-profile-btn'));
console.log('Form:', document.getElementById('edit-profile-form'));
console.log('Profile form:', document.getElementById('profile-form'));

// Mostrar/ocultar formulario de edición
document.getElementById('edit-profile-btn').addEventListener('click', function() {
    console.log('DEBUG: Botón editar clickeado');
    document.getElementById('edit-profile-form').style.display = 'block';
    document.getElementById('edit-profile-form').scrollIntoView({ behavior: 'smooth' });
});

document.getElementById('cancel-edit').addEventListener('click', function() {
    console.log('DEBUG: Cancelar clickeado');
    document.getElementById('edit-profile-form').style.display = 'none';
});

// Manejar envío del formulario con AJAX
document.getElementById('profile-form').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('DEBUG: Formulario enviado');
    
    // Validar contraseñas si se están cambiando
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    
    if (newPassword && newPassword !== confirmPassword) {
        alert('Las contraseñas no coinciden');
        return;
    }
    
    const formData = new FormData(this);
    
    // DEBUG: Mostrar datos del FormData
    for (let [key, value] of formData.entries()) {
        console.log('FormData:', key, value);
    }
    
    // Mostrar loading
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Guardando...';
    submitBtn.disabled = true;
    
    console.log('DEBUG: Enviando datos a actualizar_perfil.php');
    
    fetch('actualizar_perfil.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('DEBUG: Respuesta recibida, status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('DEBUG: Datos recibidos:', data);
        if (data.success) {
            // Actualizar la información en la interfaz
            const nombre = document.getElementById('nombre').value;
            const apellidoPaterno = document.getElementById('apellido_paterno').value;
            const apellidoMaterno = document.getElementById('apellido_materno').value;
            
            // ✅ CORREGIDO: Construir nombre completo correctamente
            let nombreCompleto = nombre.trim();
            if (apellidoPaterno.trim()) {
                nombreCompleto += ' ' + apellidoPaterno.trim();
            }
            if (apellidoMaterno.trim()) {
                nombreCompleto += ' ' + apellidoMaterno.trim();
            }
            
            document.getElementById('profile-name').textContent = nombreCompleto;
            document.getElementById('profile-description').textContent = document.getElementById('description').value;
            
            // Actualizar foto si se cambió
            if (data.new_photo) {
                const img = document.querySelector('.perfil-foto img');
                img.src = data.new_photo + '?t=' + new Date().getTime();
                console.log('DEBUG: Foto actualizada a:', data.new_photo);
            }
            
            // Actualizar otros datos
            updateProfileData();
            
            // Ocultar el formulario
            document.getElementById('edit-profile-form').style.display = 'none';
            
            alert('Perfil actualizado correctamente');
            console.log('DEBUG: Perfil actualizado exitosamente');
        } else {
            alert('Error: ' + data.message);
            console.error('DEBUG: Error del servidor:', data.message);
        }
    })
    .catch(error => {
        console.error('DEBUG: Error en fetch:', error);
        alert('Error al actualizar el perfil: ' + error.message);
    })
    .finally(() => {
        // Restaurar botón
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

// Función para actualizar datos del perfil en tiempo real
function updateProfileData() {
    const birthdate = document.getElementById('birthdate').value;
    const email = document.getElementById('email').value;
    const gender = document.getElementById('gender').options[document.getElementById('gender').selectedIndex].text;
    
    document.querySelector('.perfil-datos li:nth-child(1)').textContent = formatDate(birthdate);
    document.querySelector('.perfil-datos li:nth-child(2)').textContent = email;
    document.querySelector('.perfil-datos li:nth-child(3)').textContent = gender;
    
    console.log('DEBUG: Datos de perfil actualizados en interfaz');
}

// Función para formatear fecha de YYYY-MM-DD a DD/MM/YYYY
function formatDate(dateString) {
    const date = new Date(dateString);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

// Validación de contraseña en tiempo real
document.getElementById('new-password').addEventListener('input', function() {
    const password = this.value;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    
    if (password && !passwordRegex.test(password)) {
        this.style.borderColor = 'red';
    } else {
        this.style.borderColor = '';
    }
});

// Validar que las contraseñas coincidan
document.getElementById('confirm-password').addEventListener('input', function() {
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = this.value;
    
    if (newPassword && confirmPassword && newPassword !== confirmPassword) {
        this.style.borderColor = 'red';
    } else {
        this.style.borderColor = '';
    }
});

console.log('DEBUG: JavaScript cargado completamente');
</script>
</body>
</html>