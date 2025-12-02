<?php
session_start();

// Incluir la configuración de la base de datos
require_once 'config/database.php';

// Lista de mundiales históricos hasta 2026
function obtenerMundialesHistoricos() {
    return [
        ['id' => 1, 'año' => 1930, 'sede' => 'Uruguay', 'nombre' => 'Uruguay 1930'],
        ['id' => 2, 'año' => 1934, 'sede' => 'Italia', 'nombre' => 'Italia 1934'],
        ['id' => 3, 'año' => 1938, 'sede' => 'Francia', 'nombre' => 'Francia 1938'],
        ['id' => 4, 'año' => 1950, 'sede' => 'Brasil', 'nombre' => 'Brasil 1950'],
        ['id' => 5, 'año' => 1954, 'sede' => 'Suiza', 'nombre' => 'Suiza 1954'],
        ['id' => 6, 'año' => 1958, 'sede' => 'Suecia', 'nombre' => 'Suecia 1958'],
        ['id' => 7, 'año' => 1962, 'sede' => 'Chile', 'nombre' => 'Chile 1962'],
        ['id' => 8, 'año' => 1966, 'sede' => 'Inglaterra', 'nombre' => 'Inglaterra 1966'],
        ['id' => 9, 'año' => 1970, 'sede' => 'México', 'nombre' => 'México 1970'],
        ['id' => 10, 'año' => 1974, 'sede' => 'Alemania Occidental', 'nombre' => 'Alemania 1974'],
        ['id' => 11, 'año' => 1978, 'sede' => 'Argentina', 'nombre' => 'Argentina 1978'],
        ['id' => 12, 'año' => 1982, 'sede' => 'España', 'nombre' => 'España 1982'],
        ['id' => 13, 'año' => 1986, 'sede' => 'México', 'nombre' => 'México 1986'],
        ['id' => 14, 'año' => 1990, 'sede' => 'Italia', 'nombre' => 'Italia 1990'],
        ['id' => 15, 'año' => 1994, 'sede' => 'Estados Unidos', 'nombre' => 'USA 1994'],
        ['id' => 16, 'año' => 1998, 'sede' => 'Francia', 'nombre' => 'Francia 1998'],
        ['id' => 17, 'año' => 2002, 'sede' => 'Corea/Japón', 'nombre' => 'Corea/Japón 2002'],
        ['id' => 18, 'año' => 2006, 'sede' => 'Alemania', 'nombre' => 'Alemania 2006'],
        ['id' => 19, 'año' => 2010, 'sede' => 'Sudáfrica', 'nombre' => 'Sudáfrica 2010'],
        ['id' => 20, 'año' => 2014, 'sede' => 'Brasil', 'nombre' => 'Brasil 2014'],
        ['id' => 21, 'año' => 2018, 'sede' => 'Rusia', 'nombre' => 'Rusia 2018'],
        ['id' => 22, 'año' => 2022, 'sede' => 'Qatar', 'nombre' => 'Qatar 2022'],
        ['id' => 23, 'año' => 2026, 'sede' => 'Canadá/EEUU/México', 'nombre' => 'América del Norte 2026']
    ];
}

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'agregar_mundial':
                agregarMundial();
                break;
            case 'agregar_categoria':
                agregarCategoria();
                break;
            case 'eliminar_mundial':
                eliminarMundial();
                break;
            case 'eliminar_categoria':
                eliminarCategoria();
                break;
            case 'cambiar_estado_usuario':
                cambiarEstadoUsuario();
                break;
        }
    }
}

// Funciones para manejar la base de datos
function agregarMundial() {
    global $pdo;
    
    // Obtener el mundial seleccionado del combo box
    $mundialSeleccionado = $_POST['mundial_seleccionado'];
    $mundialesHistoricos = obtenerMundialesHistoricos();
    
    // Buscar el mundial seleccionado en la lista
    $mundialData = null;
    foreach ($mundialesHistoricos as $mundial) {
        if ($mundial['id'] == $mundialSeleccionado) {
            $mundialData = $mundial;
            break;
        }
    }
    
    if (!$mundialData) {
        $_SESSION['mensaje'] = "Error: Mundial seleccionado no válido";
        $_SESSION['tipo_mensaje'] = "error";
        header('Location: admin.php');
        exit();
    }
    
    $nombre = $mundialData['nombre'];
    $año = $mundialData['año'];
    $pais_sede = $mundialData['sede'];
    $descripcion = trim($_POST['descripcion_mundial']);
    
    // Verificar si el mundial ya existe en la base de datos
    try {
        $sql_check = "SELECT COUNT(*) as total FROM mundiales WHERE año = ? AND activo = 1";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$año]);
        $result = $stmt_check->fetch();
        
        if ($result['total'] > 0) {
            $_SESSION['mensaje'] = "Error: El mundial del año $año ya existe en el sistema";
            $_SESSION['tipo_mensaje'] = "error";
            header('Location: admin.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error al verificar mundial: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "error";
        header('Location: admin.php');
        exit();
    }
    
    // Procesar imágenes
    $logo = null;
    $imagen_representativa = null;
    
    if (isset($_FILES['logo_mundial']) && $_FILES['logo_mundial']['error'] === 0) {
        $logo = file_get_contents($_FILES['logo_mundial']['tmp_name']);
    }
    
    if (isset($_FILES['imagen_representativa']) && $_FILES['imagen_representativa']['error'] === 0) {
        $imagen_representativa = file_get_contents($_FILES['imagen_representativa']['tmp_name']);
    }
    
    try {
        $sql = "INSERT INTO mundiales (nombre, año, pais_sede, descripcion, logo, imagen_representativa, activo) 
                 VALUES (?, ?, ?, ?, ?, ?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $año, $pais_sede, $descripcion, $logo, $imagen_representativa]);
        
        $_SESSION['mensaje'] = "Mundial agregado correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error al agregar mundial: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "error";
    }
    
    header('Location: admin.php');
    exit();
}

function agregarCategoria() {
    global $pdo;
    
    $nombre = trim($_POST['nombre_categoria']);
    $descripcion = trim($_POST['descripcion_categoria']);
    
    try {
        $sql = "INSERT INTO categorias (nombre, descripcion, activo) VALUES (?, ?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $descripcion]);
        
        $_SESSION['mensaje'] = "Categoría agregada correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error al agregar categoría: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "error";
    }
    
    header('Location: admin.php');
    exit();
}

function eliminarMundial() {
    global $pdo;
    
    $id_mundial = intval($_POST['id_mundial']);
    
    try {
        // Verificar si hay publicaciones asociadas
        $sql_check = "SELECT COUNT(*) as total FROM publicaciones WHERE id_mundial = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id_mundial]);
        $result = $stmt_check->fetch();
        
        if ($result['total'] > 0) {
            $_SESSION['mensaje'] = "No se puede desactivar el mundial porque tiene publicaciones asociadas";
            $_SESSION['tipo_mensaje'] = "error";
            header('Location: admin.php');
            exit();
        }
        
        // CORRECCIÓN: Borrado Lógico (Soft Delete)
        $sql = "UPDATE mundiales SET activo = 0 WHERE id_mundial = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_mundial]);
        
        $_SESSION['mensaje'] = "Mundial desactivado correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error al desactivar mundial: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "error";
    }
    
    header('Location: admin.php');
    exit();
}

function eliminarCategoria() {
    global $pdo;
    
    $id_categoria = intval($_POST['id_categoria']);
    
    try {
        // Verificar si hay publicaciones asociadas
        $sql_check = "SELECT COUNT(*) as total FROM publicaciones WHERE id_categoria = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$id_categoria]);
        $result = $stmt_check->fetch();
        
        if ($result['total'] > 0) {
            $_SESSION['mensaje'] = "No se puede desactivar la categoría porque tiene publicaciones asociadas";
            $_SESSION['tipo_mensaje'] = "error";
            header('Location: admin.php');
            exit();
        }
        
        // CORRECCIÓN: Borrado Lógico (Soft Delete)
        $sql = "UPDATE categorias SET activo = 0 WHERE id_categoria = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_categoria]);
        
        $_SESSION['mensaje'] = "Categoría desactivada correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error al desactivar categoría: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "error";
    }
    
    header('Location: admin.php');
    exit();
}

function cambiarEstadoUsuario() {
    global $pdo;
    
    $id_usuario = intval($_POST['id_usuario']);
    $nuevo_estado = intval($_POST['nuevo_estado']);
    
    try {
        $sql = "UPDATE usuarios SET activo = ? WHERE id_usuario = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nuevo_estado, $id_usuario]);
        
        $accion = $nuevo_estado ? "activado" : "desactivado";
        $_SESSION['mensaje'] = "Usuario $accion correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error al cambiar estado del usuario: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "error";
    }
    
    header('Location: admin.php');
    exit();
}

// Obtener datos de la base de datos
function obtenerMundiales() {
    global $pdo;
    
    try {
        $sql = "SELECT m.*, 
                        (SELECT COUNT(*) FROM publicaciones p WHERE p.id_mundial = m.id_mundial) as total_publicaciones
                FROM mundiales m 
                WHERE m.activo = 1 
                ORDER BY m.año DESC";
        return $pdo->query($sql)->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function obtenerCategorias() {
    global $pdo;
    
    try {
        $sql = "SELECT c.*, 
                        (SELECT COUNT(*) FROM publicaciones p WHERE p.id_categoria = c.id_categoria) as total_publicaciones
                FROM categorias c 
                WHERE c.activo = 1 
                ORDER BY c.nombre";
        return $pdo->query($sql)->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function obtenerUsuarios() {
    global $pdo;
    
    try {
        $sql = "SELECT u.*, 
                        (SELECT COUNT(*) FROM publicaciones p WHERE p.id_usuario = u.id_usuario) as total_publicaciones,
                        (SELECT COUNT(*) FROM comentarios c WHERE c.id_usuario = u.id_usuario) as total_comentarios
                FROM usuarios u 
                ORDER BY u.fecha_registro DESC";
        return $pdo->query($sql)->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function obtenerEstadisticas() {
    global $pdo;
    
    try {
        $stats = [];
        
        // Total de mundiales
        $sql = "SELECT COUNT(*) as total FROM mundiales WHERE activo = 1";
        $stats['total_mundiales'] = $pdo->query($sql)->fetch()['total'];
        
        // Total de publicaciones pendientes
        $sql = "SELECT COUNT(*) as total FROM publicaciones WHERE estado = 'pendiente'";
        $stats['publicaciones_pendientes'] = $pdo->query($sql)->fetch()['total'];
        
        // Total de publicaciones
        $sql = "SELECT COUNT(*) as total FROM publicaciones";
        $stats['total_publicaciones'] = $pdo->query($sql)->fetch()['total'];
        
        // Total de categorías
        $sql = "SELECT COUNT(*) as total FROM categorias WHERE activo = 1";
        $stats['total_categorias'] = $pdo->query($sql)->fetch()['total'];
        
        // Total de usuarios
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE activo = 1";
        $stats['total_usuarios'] = $pdo->query($sql)->fetch()['total'];
        
        return $stats;
    } catch (PDOException $e) {
        return [
            'total_mundiales' => 0,
            'publicaciones_pendientes' => 0,
            'total_publicaciones' => 0,
            'total_categorias' => 0,
            'total_usuarios' => 0
        ];
    }
}

// Obtener datos
$mundiales = obtenerMundiales();
$categorias = obtenerCategorias();
$usuarios = obtenerUsuarios();
$estadisticas = obtenerEstadisticas();

// 🌟 CORRECCIÓN PARA EL NOMBRE COMPLETO EN EL ENCABEZADO
$nombre_admin = $_SESSION['user_nombre'] ?? '';
$apellido_paterno_admin = $_SESSION['user_apellido_paterno'] ?? '';
$apellido_materno_admin = $_SESSION['user_apellido_materno'] ?? '';

// Concatenar las partes para obtener el nombre completo
$nombre_completo_admin = trim(
    $nombre_admin . 
    ($apellido_paterno_admin ? ' ' . $apellido_paterno_admin : '') . 
    ($apellido_materno_admin ? ' ' . $apellido_materno_admin : '')
);

$nombre_a_mostrar = $nombre_completo_admin ?: 'Administrador';
// 🌟 FIN DE CORRECCIÓN PARA EL NOMBRE COMPLETO EN EL ENCABEZADO
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/LOGO.png" type="image/x-icon">
    <title>Panel de Administración - Tercer Tiempo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        /* ... (mantener todos los estilos existentes) ... */

        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .file-input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        .image-preview {
            max-width: 100px;
            max-height: 100px;
            margin-top: 10px;
            display: none;
        }

        /* Nuevos estilos para el combo box de mundiales */
        .info-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            margin-top: 10px;
        }

        .info-row {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
        }

        .info-row:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }

        .form-text {
            color: #6c757d;
            font-size: 12px;
            margin-top: 5px;
        }

        select.form-control {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            background-color: white;
        }

        select.form-control:focus {
            border-color: #003366;
            outline: none;
        }
    </style>
</head>
<body>
    <div class="admin-sidebar">
        <div class="admin-logo">Tercer Tiempo</div>
        <nav class="admin-nav">
            <a href="#" class="admin-nav-item active" data-section="dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span>dashboard</span>
            </a>
            <a href="#" class="admin-nav-item" data-section="mundiales">
                <i class="fas fa-trophy"></i>
                <span>mundiales</span>
            </a>
            <a href="#" class="admin-nav-item" data-section="categorias">
                <i class="fas fa-tags"></i>
                <span>categorías</span>
            </a>
            <a href="#" class="admin-nav-item" data-section="publicaciones">
                <i class="fas fa-file-alt"></i>
                <span>publicaciones</span>
            </a>
            <a href="#" class="admin-nav-item" data-section="usuarios">
                <i class="fas fa-users"></i>
                <span>usuarios</span>
            </a>
            <a href="logout_admin.php" class="admin-nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>salir</span>
            </a>
        </nav>
    </div>

    <div class="admin-main">
        <header class="admin-header">
            <h1 class="admin-title" id="section-title">panel de administración</h1>
            <div class="admin-user">
                <div class="user-avatar">A</div>
                <span><?php echo htmlspecialchars($nombre_a_mostrar); ?></span>
            </div>
        </header>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?php echo $_SESSION['tipo_mensaje'] === 'success' ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
            </div>
            <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
        <?php endif; ?>

        <div id="dashboard" class="admin-section active">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['total_mundiales']; ?></div>
                    <div class="stat-label">mundiales activos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['publicaciones_pendientes']; ?></div>
                    <div class="stat-label">publicaciones pendientes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['total_publicaciones']; ?></div>
                    <div class="stat-label">total publicaciones</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['total_categorias']; ?></div>
                    <div class="stat-label">categorías</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['total_usuarios']; ?></div>
                    <div class="stat-label">usuarios</div>
                </div>
            </div>
        </div>

        <div id="mundiales" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">gestión de mundiales</h2>
                <button class="btn btn-primary" id="add-mundial">
                    <i class="fas fa-plus"></i> agregar mundial
                </button>
            </div>

            <?php if (empty($mundiales)): ?>
                <div class="empty-state">
                    <i class="fas fa-trophy"></i>
                    <h3>No hay mundiales registrados</h3>
                    <p>Comienza agregando el primer mundial al sistema.</p>
                </div>
            <?php else: ?>
                <table class="table" id="tabla-mundiales">
                    <thead>
                        <tr>
                            <th>nombre</th>
                            <th>año</th>
                            <th>país sede</th>
                            <th>publicaciones</th>
                            <th>acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-tabla-mundiales">
                        <?php foreach ($mundiales as $mundial): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($mundial['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($mundial['año']); ?></td>
                            <td><?php echo htmlspecialchars($mundial['pais_sede']); ?></td>
                            <td><?php echo htmlspecialchars($mundial['total_publicaciones']); ?></td>
                            <td class="table-actions">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="eliminar_mundial">
                                    <input type="hidden" name="id_mundial" value="<?php echo $mundial['id_mundial']; ?>">
                                    <button type="submit" class="action-btn btn-danger" 
                                            onclick="return confirm('¿Estás seguro de desactivar el mundial <?php echo htmlspecialchars($mundial['nombre']); ?>? Ya no aparecerá en el sitio.')">
                                        desactivar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div id="categorias" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">gestión de categorías</h2>
                <button class="btn btn-primary" id="add-category">
                    <i class="fas fa-plus"></i> agregar categoría
                </button>
            </div>

            <?php if (empty($categorias)): ?>
                <div class="empty-state">
                    <i class="fas fa-tags"></i>
                    <h3>No hay categorías registradas</h3>
                    <p>Comienza agregando la primera categoría al sistema.</p>
                </div>
            <?php else: ?>
                <table class="table" id="tabla-categorias">
                    <thead>
                        <tr>
                            <th>nombre</th>
                            <th>descripción</th>
                            <th>publicaciones</th>
                            <th>acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-tabla-categorias">
                        <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($categoria['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($categoria['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($categoria['total_publicaciones']); ?></td>
                            <td class="table-actions">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="eliminar_categoria">
                                    <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
                                    <button type="submit" class="action-btn btn-danger" 
                                            onclick="return confirm('¿Estás seguro de desactivar la categoría <?php echo htmlspecialchars($categoria['nombre']); ?>? Ya no aparecerá en el sitio.')">
                                        desactivar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div id="publicaciones" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">publicaciones pendientes</h2>
            </div>
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <h3>Módulo en desarrollo</h3>
                <p>La gestión de publicaciones estará disponible próximamente.</p>
            </div>
        </div>

        <div id="usuarios" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">gestión de usuarios</h2>
            </div>

            <?php if (empty($usuarios)): ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3>No hay usuarios registrados</h3>
                    <p>No se han encontrado usuarios en el sistema.</p>
                </div>
            <?php else: ?>
                <table class="table" id="tabla-usuarios">
                    <thead>
                        <tr>
                            <th>nombre</th>
                            <th>email</th>
                            <th>publicaciones</th>
                            <th>comentarios</th>
                            <th>fecha registro</th>
                            <th>estado</th>
                            <th>acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['nombres']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['total_publicaciones']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['total_comentarios']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                            <td>
                                <?php if ($usuario['activo']): ?>
                                    <span class="badge badge-success">activo</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="table-actions">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="cambiar_estado_usuario">
                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                    <input type="hidden" name="nuevo_estado" value="<?php echo $usuario['activo'] ? 0 : 1; ?>">
                                    <button type="submit" class="action-btn <?php echo $usuario['activo'] ? 'btn-danger' : 'btn-primary'; ?>">
                                        <?php echo $usuario['activo'] ? 'desactivar' : 'activar'; ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para agregar mundial ACTUALIZADO con combo box -->
    <div id="modal-mundial" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">agregar nuevo mundial</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form method="POST" id="form-mundial" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="agregar_mundial">
                    
                    <div class="form-group">
                        <label class="form-label">seleccionar mundial</label>
                        <select class="form-control" name="mundial_seleccionado" id="select-mundial" required>
                            <option value="">-- Selecciona un mundial --</option>
                            <?php 
                            $mundialesHistoricos = obtenerMundialesHistoricos();
                            $mundialesExistentes = obtenerMundiales(); // Mundiales ya en la BD
                            
                            // Crear array de años existentes para filtrar
                            $añosExistentes = [];
                            foreach ($mundialesExistentes as $mundialExistente) {
                                $añosExistentes[] = $mundialExistente['año'];
                            }
                            
                            foreach ($mundialesHistoricos as $mundial): 
                                // Mostrar solo mundiales que no estén ya en la base de datos
                                if (!in_array($mundial['año'], $añosExistentes)):
                            ?>
                                <option value="<?php echo $mundial['id']; ?>">
                                    <?php echo $mundial['año'] . ' - ' . $mundial['sede'] . ' (' . $mundial['nombre'] . ')'; ?>
                                </option>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </select>
                        <small class="form-text">Solo se muestran mundiales que no han sido agregados previamente</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">información del mundial seleccionado</label>
                        <div id="info-mundial" class="info-box" style="display: none;">
                            <div class="info-row">
                                <strong>Nombre:</strong> <span id="info-nombre"></span>
                            </div>
                            <div class="info-row">
                                <strong>Año:</strong> <span id="info-año"></span>
                            </div>
                            <div class="info-row">
                                <strong>Sede:</strong> <span id="info-sede"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">logo del mundial</label>
                        <input type="file" class="file-input" name="logo_mundial" accept="image/*">
                        <img id="preview-logo" class="image-preview" alt="Vista previa del logo">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">imagen representativa</label>
                        <input type="file" class="file-input" name="imagen_representativa" accept="image/*">
                        <img id="preview-imagen" class="image-preview" alt="Vista previa de imagen representativa">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">descripción adicional</label>
                        <textarea class="form-control" name="descripcion_mundial" rows="3" placeholder="Agrega una descripción personalizada para este mundial..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancel-mundial">cancelar</button>
                    <button type="submit" class="btn btn-primary">guardar mundial</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-categoria" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">agregar nueva categoría</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form method="POST" id="form-categoria">
                <div class="modal-body">
                    <input type="hidden" name="action" value="agregar_categoria">
                    <div class="form-group">
                        <label class="form-label">nombre de la categoría</label>
                        <input type="text" class="form-control" name="nombre_categoria" placeholder="Ej: goles espectaculares" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">descripción</label>
                        <textarea class="form-control" name="descripcion_categoria" rows="3" placeholder="Breve descripción de la categoría..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancel-categoria">cancelar</button>
                    <button type="submit" class="btn btn-primary">guardar categoría</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Navegación entre secciones
        document.querySelectorAll('.admin-nav-item').forEach(item => {
            item.addEventListener('click', function(e) {

if (this.getAttribute('href') === 'logout_admin.php') {
            return; // Permitir que el navegador siga el enlace normalmente
        }

                e.preventDefault();
                
                // Remover clase active de todos los items
                document.querySelectorAll('.admin-nav-item').forEach(nav => {
                    nav.classList.remove('active');
                });
                
                // Agregar clase active al item clickeado
                this.classList.add('active');
                
                // Ocultar todas las secciones
                document.querySelectorAll('.admin-section').forEach(section => {
                    section.classList.remove('active');
                });
                
                // Mostrar la sección correspondiente
                const sectionId = this.getAttribute('data-section');
                if (sectionId) {
                    document.getElementById(sectionId).classList.add('active');
                    document.getElementById('section-title').textContent = 
                        this.querySelector('span').textContent;
                }
            });
        });

        // Modal para agregar mundial
        const modalMundial = document.getElementById('modal-mundial');
        const addMundialBtn = document.getElementById('add-mundial');
        const closeModalBtns = document.querySelectorAll('.modal-close, #cancel-mundial');

        addMundialBtn.addEventListener('click', () => {
            modalMundial.style.display = 'flex';
        });

        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modalMundial.style.display = 'none';
                // Limpiar el formulario al cerrar
                document.getElementById('select-mundial').value = '';
                document.getElementById('info-mundial').style.display = 'none';
                document.getElementById('form-mundial').reset();
            });
        });

        // Modal para agregar categoría
        const modalCategoria = document.getElementById('modal-categoria');
        const addCategoriaBtn = document.getElementById('add-category');
        const closeCategoriaBtns = document.querySelectorAll('.modal-close, #cancel-categoria');

        addCategoriaBtn.addEventListener('click', () => {
            modalCategoria.style.display = 'flex';
        });

        closeCategoriaBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modalCategoria.style.display = 'none';
            });
        });

        // Cerrar modales al hacer clic fuera del contenido
        window.addEventListener('click', (e) => {
            if (e.target === modalMundial) {
                modalMundial.style.display = 'none';
                // Limpiar el formulario al cerrar
                document.getElementById('select-mundial').value = '';
                document.getElementById('info-mundial').style.display = 'none';
                document.getElementById('form-mundial').reset();
            }
            if (e.target === modalCategoria) {
                modalCategoria.style.display = 'none';
            }
        });

        // Manejar la selección del mundial en el combo box
        document.getElementById('select-mundial').addEventListener('change', function(e) {
            const mundialId = this.value;
            const infoBox = document.getElementById('info-mundial');
            
            if (mundialId) {
                // Los datos de los mundiales están hardcodeados en PHP, los pasamos a JS
                const mundialesHistoricos = [
                    <?php foreach (obtenerMundialesHistoricos() as $mundial): ?>
                    {
                        id: <?php echo $mundial['id']; ?>,
                        nombre: "<?php echo $mundial['nombre']; ?>",
                        año: <?php echo $mundial['año']; ?>,
                        sede: "<?php echo $mundial['sede']; ?>"
                    },
                    <?php endforeach; ?>
                ];
                
                const mundialSeleccionado = mundialesHistoricos.find(m => m.id == mundialId);
                if (mundialSeleccionado) {
                    document.getElementById('info-nombre').textContent = mundialSeleccionado.nombre;
                    document.getElementById('info-año').textContent = mundialSeleccionado.año;
                    document.getElementById('info-sede').textContent = mundialSeleccionado.sede;
                    infoBox.style.display = 'block';
                }
            } else {
                infoBox.style.display = 'none';
            }
        });

        // Vista previa de imágenes
        document.querySelector('input[name="logo_mundial"]').addEventListener('change', function(e) {
            const preview = document.getElementById('preview-logo');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        document.querySelector('input[name="imagen_representativa"]').addEventListener('change', function(e) {
            const preview = document.getElementById('preview-imagen');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Agregar estilo para botón outline
        const style = document.createElement('style');
        style.textContent = `
            .btn-outline {
                background: transparent;
                border: 1px solid #003366;
                color: #003366;
            }
            .btn-outline:hover {
                background: #003366;
                color: white;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>