
// Previsualización de foto
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('previewImg');
    const placeholder = document.querySelector('.foto-placeholder');
    
    if (file) {
        // Validar tamaño máximo (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('La imagen es demasiado grande. Máximo 5MB permitido.');
            this.value = '';
            return;
        }
        
        // Validar tipo de archivo
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Formato de archivo no válido. Solo se permiten JPG, PNG y GIF.');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        }
        reader.readAsDataURL(file);
    }
});

// Validación de contraseña en tiempo real
document.getElementById('confirmar_contrasena').addEventListener('input', function() {
    const password = document.getElementById('contrasena').value;
    const confirmPassword = this.value;
    const matchElement = document.getElementById('passwordMatch');
    
    if (confirmPassword === '') {
        matchElement.innerHTML = '';
        matchElement.className = 'password-match';
    } else if (password === confirmPassword) {
        matchElement.innerHTML = '<small style="color: green;">✓ Las contraseñas coinciden</small>';
        matchElement.className = 'password-match valid';
    } else {
        matchElement.innerHTML = '<small style="color: red;">✗ Las contraseñas no coinciden</small>';
        matchElement.className = 'password-match invalid';
    }
});

// Mostrar/ocultar requisitos de contraseña
document.getElementById('contrasena').addEventListener('focus', function() {
    this.parentNode.querySelector('.password-requirements').style.opacity = '1';
});

document.getElementById('contrasena').addEventListener('blur', function() {
    if (this.value === '') {
        this.parentNode.querySelector('.password-requirements').style.opacity = '0.7';
    }
});

// Validación del formulario
document.getElementById('formRegistro').addEventListener('submit', function(e) {
    const password = document.getElementById('contrasena').value;
    const confirmPassword = document.getElementById('confirmar_contrasena').value;
    
    // Validar requisitos de contraseña
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':\\|,.<>\/?]).{8,}$/;
    
    if (!passwordRegex.test(password)) {
        e.preventDefault();
        alert('La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial.');
        return false;
    }
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Las contraseñas no coinciden.');
        return false;
    }
    
    // Validar edad
    const fechaNacimiento = new Date(document.getElementById('fecha_nacimiento').value);
    const hoy = new Date();
    let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
    const mes = hoy.getMonth() - fechaNacimiento.getMonth();
    
    if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
        edad--;
    }
    
    if (edad < 12) {
        e.preventDefault();
        alert('Debes ser mayor de 12 años para registrarte.');
        return false;
    }
    
    // Validar que todos los campos requeridos estén llenos
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    let allValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderBottomColor = '#e74c3c';
            allValid = false;
        } else {
            field.style.borderBottomColor = '';
        }
    });
    
    if (!allValid) {
        e.preventDefault();
        alert('Por favor, completa todos los campos requeridos.');
        return false;
    }
    
    // Mostrar mensaje de éxito
    alert('¡Registro exitoso! Redirigiendo a tu perfil...');
    return true;
});

// Validación en tiempo real para campos requeridos
document.querySelectorAll('input[required], select[required]').forEach(field => {
    field.addEventListener('blur', function() {
        if (!this.value.trim()) {
            this.style.borderBottomColor = '#e74c3c';
        } else {
            this.style.borderBottomColor = '';
        }
    });
    
    field.addEventListener('input', function() {
        if (this.value.trim()) {
            this.style.borderBottomColor = '';
        }
    });
});

// Mejorar la experiencia del formulario de fecha
document.getElementById('fecha_nacimiento').addEventListener('change', function() {
    const fechaNacimiento = new Date(this.value);
    const hoy = new Date();
    let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
    const mes = hoy.getMonth() - fechaNacimiento.getMonth();
    
    if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
        edad--;
    }
    
    if (edad < 12) {
        this.style.borderBottomColor = '#e74c3c';
    } else {
        this.style.borderBottomColor = '';
    }
});
