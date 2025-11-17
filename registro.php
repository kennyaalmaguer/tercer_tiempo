<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/LOGO.png" type="image/x-icon">
    <title>Tercer Tiempo - Registro</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100..1000&family=EB+Garamond:wght@400..800&family=Imperial+Script&family=Playfair+Display:wght@400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/registro.css">

    <style>
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid #f5c6cb;
        }
        
        .success-message {
            background: #d1edff;
            color: #0c5460;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid #bee5eb;
        }
        
        .password-match.valid {
            color: #28a745;
            font-weight: bold;
        }
        
        .password-match.invalid {
            color: #dc3545;
            font-weight: bold;
        }
        
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <img src="img/logo1.png" alt="Logo" class="logo-img">
        <div class="logo"><span class="imperial">T</span><span class="playfair-display">ERCER TIEMPO</span></div>
    </div>
    
    <nav class="menu">
        <ul>
            <li><a href="login.php">LOG IN</a></li>
        </ul>
    </nav>
</header>

<div class="registro-wrapper">
    <div class="registro-container">
        <div class="registro-form">
            <form id="formRegistro" method="POST" enctype="multipart/form-data">
                <p class="registro-title"><span class="font1">R</span><span class="font2">egistro de </span><span class="font1">usuario</span></p>
                
                <div id="messageContainer"></div>
                
                <div class="form-columns">
                    <!-- Columna Izquierda -->
                    <div class="form-column">
                        <!-- Información Personal -->
                        <div class="form-section">
                            <h3 class="section-title">Información Personal</h3>
                            
                            <div class="input-container">
                                <input type="text" name="nombre" id="nombre" placeholder=" " required>
                                <label for="nombre">Nombre(s)</label>
                            </div>
                            
                            <div class="input-group">
                                <div class="input-container">
                                    <input type="text" name="apellido_paterno" id="apellido_paterno" placeholder=" " required>
                                    <label for="apellido_paterno">Apellido Paterno</label>
                                </div>
                                
                                <div class="input-container">
                                    <input type="text" name="apellido_materno" id="apellido_materno" placeholder=" ">
                                    <label for="apellido_materno">Apellido Materno</label>
                                </div>
                            </div>

                            <div class="input-group">
                                <div class="input-container">
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" placeholder=" " required max="<?php echo date('Y-m-d', strtotime('-12 years')); ?>">
                                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                    <small class="edad-info">Debes ser mayor de 12 años</small>
                                </div>
                                
                                <div class="input-container">
                                    <select name="genero" id="genero" required>
                                        <option value=""></option>
                                        <option value="masculino">Masculino</option>
                                        <option value="femenino">Femenino</option>
                                    </select>
                                    <label for="genero">Género</label>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Ubicación -->
                        <div class="form-section">
                            <h3 class="section-title">Información de Ubicación</h3>
                            
                            <div class="input-container">
                                <select name="pais_nacimiento" id="pais_nacimiento" required>
                                    <option value=""></option>
                                    <option value="mx">México</option>
                                    <option value="ar">Argentina</option>
                                    <option value="es">España</option>
                                    <option value="co">Colombia</option>
                                    <option value="br">Brasil</option>
                                    <option value="us">Estados Unidos</option>
                                    <option value="fr">Francia</option>
                                    <option value="de">Alemania</option>
                                    <option value="it">Italia</option>
                                    <option value="uk">Reino Unido</option>
                                </select>
                                <label for="pais_nacimiento">País de Nacimiento</label>
                            </div>
                            
                            <div class="input-container">
                                <select name="nacionalidad" id="nacionalidad" required>
                                    <option value=""></option>
                                    <option value="mx">Mexicana</option>
                                    <option value="ar">Argentina</option>
                                    <option value="es">Española</option>
                                    <option value="co">Colombiana</option>
                                    <option value="br">Brasileña</option>
                                    <option value="us">Estadounidense</option>
                                    <option value="fr">Francesa</option>
                                    <option value="de">Alemana</option>
                                    <option value="it">Italiana</option>
                                    <option value="uk">Británica</option>
                                </select>
                                <label for="nacionalidad">Nacionalidad</label>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="form-column">
                        <!-- Foto de Perfil -->
                        <div class="form-section">
                            <h3 class="section-title">Foto de Perfil</h3>
                            
                            <div class="foto-container">
                                <div class="foto-preview" id="fotoPreview">
                                    <div class="foto-placeholder">
                                        <i class="fas fa-camera"></i>
                                        <span>Selecciona una foto</span>
                                    </div>
                                    <img id="previewImg" src="" alt="Vista previa">
                                    <div class="foto-overlay"></div>
                                </div>
                                
                                <div class="foto-upload">
                                    <label for="foto" class="upload-btn">
                                        <i class="fas fa-upload"></i>
                                        Seleccionar Foto
                                    </label>
                                    <input type="file" name="foto" id="foto" accept="image/*" style="display: none;">
                                    <small>Formatos: JPG, PNG, GIF (Máx. 5MB)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Cuenta -->
                        <div class="form-section">
                            <h3 class="section-title">Información de Cuenta</h3>
                            
                            <div class="input-container">
                                <input type="email" name="correo" id="correo" placeholder=" " required>
                                <label for="correo">Correo Electrónico</label>
                            </div>
                            
                            <div class="input-container password-container">
                                <input type="password" name="contrasena" id="contrasena" placeholder=" " required 
                                       pattern="^(?=.*[a-záéíóúüñ])(?=.*[A-ZÁÉÍÓÚÜÑ])(?=.*\d)(?=.*[!@#$%^&*()_+=\-\[\]{};':|,.<>?/]).{8,}$"
                                       title="La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial">
                                <label for="contrasena">Contraseña</label>
                                <div class="password-requirements">
                                    <small>Requisitos: Mínimo 8 caracteres, incluir mayúscula, minúscula, número y carácter especial</small>
                                </div>
                            </div>
                            
                            <div class="input-container">
                                <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" placeholder=" " required>
                                <label for="confirmar_contrasena">Confirmar Contraseña</label>
                                <div class="password-match" id="passwordMatch"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="registro-btn" type="submit" id="submitBtn">
                        <i class="fas fa-user-plus"></i>
                        Registrarse
                    </button>

                    <p class="sign_up">¿Ya tienes una cuenta? <a href="login.php">Inicia Sesión</a></p>
                </div>
            </form>
        </div>
    </div>
</div><br><br>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formRegistro');
    const messageContainer = document.getElementById('messageContainer');
    const submitBtn = document.getElementById('submitBtn');
    const originalBtnText = submitBtn.innerHTML;

    // Preview de imagen
    const fotoInput = document.getElementById('foto');
    const previewImg = document.getElementById('previewImg');
    const fotoPlaceholder = document.querySelector('.foto-placeholder');

    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                fotoPlaceholder.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });

    // Validación en tiempo real de contraseñas
    document.getElementById('confirmar_contrasena').addEventListener('input', function() {
        const password = document.getElementById('contrasena').value;
        const confirmPassword = this.value;
        const matchElement = document.getElementById('passwordMatch');
        
        if (confirmPassword === '') {
            matchElement.innerHTML = '';
            matchElement.className = 'password-match';
        } else if (password === confirmPassword) {
            matchElement.innerHTML = '✓ Las contraseñas coinciden';
            matchElement.className = 'password-match valid';
        } else {
            matchElement.innerHTML = '✗ Las contraseñas no coinciden';
            matchElement.className = 'password-match invalid';
        }
    });

    // Manejo del envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Mostrar loading
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registrando...';
        submitBtn.disabled = true;
        form.classList.add('loading');
        
        const formData = new FormData(form);
        
        fetch('procesar_registro.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error de red');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                let errorMessage = 'Errores encontrados:<br>' + data.errors.join('<br>');
                showMessage(errorMessage, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al procesar el registro. Intenta nuevamente.', 'error');
        })
        .finally(() => {
            // Restaurar botón
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
            form.classList.remove('loading');
        });
    });

    function showMessage(message, type) {
        messageContainer.innerHTML = `
            <div class="${type === 'error' ? 'error-message' : 'success-message'}">
                ${message}
            </div>
        `;
        
        // Hacer scroll al mensaje
        messageContainer.scrollIntoView({ behavior: 'smooth' });
    }
});
</script>
</body>
</html>