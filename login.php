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

            <li><a href="publicaciones.php">PUBLICACIONES </a></li>
            <li><a href="login.php">LOG IN </a></li>
        </ul>
    </nav>
</header>
<br>

<div class="login-container">
  <form id="loginForm" method="POST">
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

    <p class="separator">
        <span class="playfair-display">ó</span>
    </p>

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
    
    const formData = new FormData(this);
    
    fetch('procesar_login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirigir al perfil
            window.location.href = data.redirect;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar el login');
    });
});
</script>
</body>
</html>
