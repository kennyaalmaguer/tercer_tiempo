<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">


    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/LOGO.png" type="image/x-icon">

    <title>Tercer Tiempo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100..1000&family=EB+Garamond:wght@400..800&family=Imperial+Script&family=Playfair+Display:wght@400..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/loginstyle.css">

</head>
<body>

<header>

<div class="logo">
        <img src="img/logo1.png" alt="Logo" class="logo-img">
        <div class="logo"><span class="imperial">T</span><span class="playfair-display">ERCER TIEMPO</span> </div>
    </div>
    
 
    </div>
  <nav class="menu">
    <ul>
        <li><a href="index.php">INICIO</a></li>
        <li><a href="publicaciones.php">PUBLICACIONES</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li><a href="perfil.php">MI PERFIL</a></li>
            <li><a href="logout.php">CERRAR SESIÓN</a></li>
        <?php else: ?>
            
            <li><a href="registro.php">REGISTRARSE</a></li>
        <?php endif; ?>
    </ul>
</nav>
</header>
<br>

<div class="login-container">
  <form id="loginForm" class="login_form" method="POST">
    <p class="login-title"><span class="font1">I</span><span class="font2">nicio</span> <span class="font1">sesión</span></p><br>

    <div class="input-container">
        <span class="playfair-display">CORREO ELECTRONICO</span>
        <input type="email" name="email" id="email" placeholder=" " required>
        <label for="email">Correo Electrónico</label>
    </div>
  
    <div class="input-container">
        <span class="playfair-display">CONTRASEÑA</span>
        <input type="password" name="password" id="password" placeholder=" " required>
        <label for="password">Contraseña</label>
    </div>

    <button class="playfair-display" type="submit">Iniciar Sesión</button>


    <p class="sign_up">¿No tienes un cuenta? <a href="registro.php">Registrate</a></p>
</form>


  <div class="login-image">
        <img src="img/principal3.jpg" alt="Imagen de inicio de sesión">
    </div>
  
    </div>
</div>
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    console.log("🔄 Iniciando proceso de login...");
    
    const formData = new FormData(this);
    const email = document.getElementById('email').value;
    
    console.log("📧 Email ingresado:", email);
    console.log("🔑 Password ingresado:", "***" + document.getElementById('password').value.slice(-2));
    
    // Mostrar loading
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Procesando...';
    submitBtn.disabled = true;
    
    fetch('procesar_login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log("📡 Respuesta HTTP recibida. Status:", response.status);
        console.log("📋 Headers:", Object.fromEntries(response.headers.entries()));
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("✅ JSON parseado correctamente:");
        console.log("📊 Datos recibidos:", data);
        
        if (data.success) {
            console.log("🎯 Login exitoso - Redirigiendo a:", data.redirect);
            console.log("🔍 Debug info:", data.debug || 'No disponible');
            
            // Redirigir al perfil
            window.location.href = data.redirect;
        } else {
            console.error("❌ Error en login:", data.message);
            
            // Mostrar error detallado
            let errorMsg = data.message;
            if (data.debug) {
                errorMsg += `\n\nDebug Info:\n`;
                for (let key in data.debug) {
                    errorMsg += `${key}: ${data.debug[key]}\n`;
                }
            }
            
            alert('❌ Error: ' + errorMsg);
        }
    })
    .catch(error => {
        console.error("💥 Error crítico:", error);
        
        if (error instanceof SyntaxError) {
            console.error("📝 El servidor no devolvió JSON válido");
            alert('❌ Error: El servidor respondió con formato incorrecto. Revisa la consola para más detalles.');
        } else {
            console.error("🔧 Error de red o servidor:", error);
            alert('❌ Error de conexión: ' + error.message);
        }
    })
    .finally(() => {
        // Restaurar botón
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        console.log("🏁 Proceso de login finalizado");
    });
});

// Función para probar manualmente
function testLogin() {
    console.clear();
    console.log("🧪 INICIANDO PRUEBA MANUAL DE LOGIN");
    
    // Llenar datos de prueba
    document.getElementById('email').value = 'admin@tercertiempo.com';
    document.getElementById('password').value = 'admin123';
    
    console.log("📝 Datos de prueba cargados automáticamente");
    console.log("👉 Envía el formulario para continuar...");
}

// Ejecutar prueba automática al cargar la página (opcional)
// window.addEventListener('load', function() {
//     console.log("🔧 Debug mode activado - Revisa la consola (F12)");
//     console.log("💡 Usa testLogin() para llenar datos de prueba automáticamente");
// });
</script>

<!-- Agregar este div para mostrar logs en la página -->
<div id="debugConsole" style="display: none; background: #000; color: #0f0; padding: 10px; margin: 10px 0; border-radius: 5px; font-family: monospace; max-height: 200px; overflow-y: auto;">
    <strong>Debug Console:</strong>
    <div id="debugLogs"></div>
</div>

<!-- Botón para mostrar/ocultar consola -->
<button type="button" onclick="toggleDebug()" style="background: #666; color: white; border: none; padding: 5px 10px; border-radius: 3px; font-size: 12px; margin: 5px;">
    🐛 Mostrar Consola Debug
</button>

<script>
// Función para mostrar logs en la página
function logToPage(message) {
    const debugConsole = document.getElementById('debugConsole');
    const debugLogs = document.getElementById('debugLogs');
    
    if (debugLogs) {
        const timestamp = new Date().toLocaleTimeString();
        debugLogs.innerHTML += `[${timestamp}] ${message}<br>`;
        debugConsole.scrollTop = debugConsole.scrollHeight;
    }
}

// Función para toggle de la consola
function toggleDebug() {
    const debugConsole = document.getElementById('debugConsole');
    if (debugConsole.style.display === 'none') {
        debugConsole.style.display = 'block';
        
        // Redirigir console.log a la página también
        const originalLog = console.log;
        console.log = function(...args) {
            originalLog.apply(console, args);
            logToPage(args.join(' '));
        };
        
        const originalError = console.error;
        console.error = function(...args) {
            originalError.apply(console, args);
            logToPage('❌ ' + args.join(' '));
        };
    } else {
        debugConsole.style.display = 'none';
    }
}

// Sobrescribir console.log para capturar logs iniciales
console.log("🔧 Sistema de debug activado");
console.log("💡 Usa testLogin() en la consola para llenar datos automáticamente");
</script>
</body>
</html>
