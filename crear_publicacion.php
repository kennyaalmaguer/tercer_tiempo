<?php
session_start();
require_once 'config/database.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
    exit;
}

$response = ['success' => false, 'message' => ''];

try {
    // Validar datos requeridos
    $required_fields = ['titulo', 'descripcion', 'id_categoria', 'id_mundial'];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $response['message'] = "El campo " . str_replace('_', ' ', $field) . " es requerido";
            echo json_encode($response);
            exit;
        }
    }
    
    // Sanitizar datos
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $id_categoria = intval($_POST['id_categoria']);
    $id_mundial = intval($_POST['id_mundial']);
    $seleccion_relacionada = isset($_POST['id_seleccion']) && !empty($_POST['id_seleccion']) ? intval($_POST['id_seleccion']) : null;
    
    // Validar longitud de título
    if (strlen($titulo) > 200) {
        $response['message'] = "El título no puede exceder los 200 caracteres";
        echo json_encode($response);
        exit;
    }
    
    // Validar que existan las categorías y mundiales
    global $pdo;
    
    // Verificar categoría
    $stmt = $pdo->prepare("SELECT id_categoria FROM categorias WHERE id_categoria = ? AND activo = 1");
    $stmt->execute([$id_categoria]);
    if (!$stmt->fetch()) {
        $response['message'] = "Categoría no válida";
        echo json_encode($response);
        exit;
    }
    
    // Verificar mundial
    $stmt = $pdo->prepare("SELECT id_mundial FROM mundiales WHERE id_mundial = ? AND activo = 1");
    $stmt->execute([$id_mundial]);
    if (!$stmt->fetch()) {
        $response['message'] = "Mundial no válido";
        echo json_encode($response);
        exit;
    }
    
    // Verificar selección si se proporcionó
    if ($seleccion_relacionada) {
        $stmt = $pdo->prepare("SELECT id_seleccion FROM selecciones WHERE id_seleccion = ?");
        $stmt->execute([$seleccion_relacionada]);
        if (!$stmt->fetch()) {
            $response['message'] = "Selección no válida";
            echo json_encode($response);
            exit;
        }
    }
    
    // Verificar que haya al menos una imagen o video
    if (empty($_FILES['imagenes']['name'][0]) && empty($_FILES['videos']['name'][0])) {
        $response['message'] = "Debes subir al menos una imagen o video";
        echo json_encode($response);
        exit;
    }
    
    // Insertar publicación (estado pendiente por defecto)
    $sql = "INSERT INTO publicaciones 
            (id_usuario, id_mundial, id_categoria, titulo, descripcion, seleccion_relacionada, fecha_elaboracion, estado) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), 'pendiente')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_SESSION['user_id'],
        $id_mundial,
        $id_categoria,
        $titulo,
        $descripcion,
        $seleccion_relacionada
    ]);
    
    $id_publicacion = $pdo->lastInsertId();
    
    // Función para subir archivos
    function subirArchivo($file, $id_publicacion, $tipo, $pdo) {
        $upload_dir = 'uploads/publicaciones/';
        
        // Crear directorio si no existe
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nombre_archivo = uniqid() . '_' . time() . '.' . $extension;
        $ruta_completa = $upload_dir . $nombre_archivo;
        
        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $ruta_completa)) {
            // Insertar en base de datos
            $sql = "INSERT INTO contenido_multimedia (id_publicacion, url_archivo, tipo_contenido, orden) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $id_publicacion,
                $nombre_archivo,
                $tipo,
                isset($_POST['orden'][$tipo]) ? $_POST['orden'][$tipo] : null
            ]);
            return true;
        }
        return false;
    }
    
    // Procesar imágenes
    $imagenes_subidas = 0;
    if (!empty($_FILES['imagenes']['name'][0])) {
        $imagenes = $_FILES['imagenes'];
        $count = count($imagenes['name']);
        
        for ($i = 0; $i < $count; $i++) {
            if ($imagenes['error'][$i] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $imagenes['name'][$i],
                    'type' => $imagenes['type'][$i],
                    'tmp_name' => $imagenes['tmp_name'][$i],
                    'error' => $imagenes['error'][$i],
                    'size' => $imagenes['size'][$i]
                ];
                
                // Validar tipo de imagen
                $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file['type'], $allowed_image_types)) {
                    continue;
                }
                
                // Validar tamaño (máximo 5MB)
                if ($file['size'] > 5 * 1024 * 1024) {
                    continue;
                }
                
                if (subirArchivo($file, $id_publicacion, 'imagen', $pdo)) {
                    $imagenes_subidas++;
                }
            }
        }
    }
    
    // Procesar videos
    $videos_subidos = 0;
    if (!empty($_FILES['videos']['name'][0])) {
        $videos = $_FILES['videos'];
        $count = count($videos['name']);
        
        for ($i = 0; $i < $count; $i++) {
            if ($videos['error'][$i] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $videos['name'][$i],
                    'type' => $videos['type'][$i],
                    'tmp_name' => $videos['tmp_name'][$i],
                    'error' => $videos['error'][$i],
                    'size' => $videos['size'][$i]
                ];
                
                // Validar tipo de video
                $allowed_video_types = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'];
                if (!in_array($file['type'], $allowed_video_types)) {
                    continue;
                }
                
                // Validar tamaño (máximo 50MB)
                if ($file['size'] > 50 * 1024 * 1024) {
                    continue;
                }
                
                if (subirArchivo($file, $id_publicacion, 'video', $pdo)) {
                    $videos_subidos++;
                }
            }
        }
    }
    
    // Verificar que se hayan subido archivos
    if ($imagenes_subidas == 0 && $videos_subidos == 0) {
        // Eliminar la publicación si no se subieron archivos
        $pdo->prepare("DELETE FROM publicaciones WHERE id_publicacion = ?")->execute([$id_publicacion]);
        $response['message'] = "Error al subir los archivos multimedia";
        echo json_encode($response);
        exit;
    }
    
    $response['success'] = true;
    $response['message'] = "Publicación creada exitosamente. Está pendiente de aprobación.";
    $response['id_publicacion'] = $id_publicacion;
    
} catch (Exception $e) {
    $response['message'] = "Error: " . $e->getMessage();
}

echo json_encode($response);
?>