<?php
session_start();
require_once 'config/database.php';

$user_id = $_SESSION['user_id'] ?? null;
$user_nombre = $_SESSION['user_nombre'] ?? 'invitado'; 
$user_foto_perfil = $_SESSION['user_foto'] ?? 'img/profile2.jpg'; 

// --- 1. Obtención de Categorías ---
$categorias = [];
try {
    global $pdo;
    $sql_categorias = "SELECT id_categoria, nombre FROM categorias WHERE activo = 1 ORDER BY nombre ASC";
    $stmt_c = $pdo->query($sql_categorias);
    $categorias = $stmt_c->fetchAll();
} catch (PDOException $e) {
    // Manejo de error silencioso
}

// --- 2. Obtención de Mundiales ---
$mundiales_list = [];
try {
    global $pdo;
    $sql_mundiales = "SELECT id_mundial, nombre, año FROM mundiales WHERE activo = 1 ORDER BY año DESC";
    $stmt_m = $pdo->query($sql_mundiales);
    $mundiales_list = $stmt_m->fetchAll();
} catch (PDOException $e) {
    // Manejo de error silencioso
}

// --- 3. Obtención de Selecciones ---
$selecciones_list = [];
try {
    global $pdo;
    $sql_selecciones = "SELECT id_seleccion, nombre FROM selecciones ORDER BY nombre ASC";
    $stmt_s = $pdo->query($sql_selecciones);
    $selecciones_list = $stmt_s->fetchAll();
} catch (PDOException $e) {
    // Manejo de error silencioso
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/LOGO.png" type="image/x-icon">
    <title>Tercer Tiempo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/publicaciones.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <div class="nav-item">
        <?php echo "<p>Tt</p>"; ?>
    </div>

    <div class="search-bar">
        <input type="text" placeholder="buscar...">
    </div>

    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="index.php">inicio</a>
            <a href="perfil.php">perfil</a>
            <a href="logout.php">cerrar sesión</a>
        <?php else: ?>
            <a href="publicaciones.php">publicaciones</a>
            <a href="registro.php">registro</a>
            <a href="login.php">log in</a>
        <?php endif; ?>
    </nav>
</header>

<section class="segunda-seccion">
</section>

<section class="categorias-sec">
    <h2 class="categorias-titulo">categorías</h2>
    <div class="categorias-container">
        <button class="categoria-btn active" data-categoria-id="0">todas</button>
        
        <?php
        if ($categorias) {
            foreach ($categorias as $categoria) {
                $id = htmlspecialchars($categoria['id_categoria']);
                $nombre = htmlspecialchars(strtolower($categoria['nombre']));
                
                echo "<button class=\"categoria-btn\" data-categoria-id=\"{$id}\">{$nombre}</button>";
            }
        } else {
            echo "";
        }
        ?>
    </div>
</section>

<?php if (isset($_SESSION['user_id'])): ?>
<section class="create-post-sec">
    <div class="create-post-container">
        <div class="create-post-box">
            <div class="create-post-header">
                <div class="create-post-avatar">
                    <img src="<?php echo htmlspecialchars($user_foto_perfil); ?>" alt="avatar de perfil">
                </div>
                <div class="create-post-input">
                    <input 
        type="text" 
            id="post-title" 
            class="post-title-input"
            placeholder="agrega un título para tu publicación..."
        >
                    <textarea id="post-textarea" placeholder="¿qué estás pensando, <?php echo htmlspecialchars($user_nombre); ?>?"></textarea>
                </div>
            </div>
            
            <div class="image-preview-multiple" id="image-preview-multiple"></div>
            <div class="video-preview" id="video-preview"></div>
            <div class="create-post-options">
                <div class="post-options">
                    <label for="image-upload" class="option-btn">
                        <i class="option-icon fas fa-image"></i>
                        <span>foto</span>
                    </label>
                    <input type="file" id="image-upload" accept="image/*" multiple style="display: none;">
                    
                    <label for="video-upload" class="option-btn">
                        <i class="option-icon fas fa-video"></i>
                        <span>video</span>
                    </label>
                    <input type="file" id="video-upload" accept="video/*" multiple style="display: none;">
                    
                    <button type="button" class="option-btn" id="clear-images-btn">
                        <i class="option-icon fas fa-trash"></i>
                        <span>limpiar fotos</span>
                    </button>
                    
                    <div class="option-btn select-option-btn" data-target="mundial">
                        <i class="option-icon fas fa-trophy"></i>
                        <span id="selected-mundial-name">mundial</span>
                        <input type="hidden" id="selected-mundial-id" value="">
                    </div>

                    <div class="option-btn select-option-btn" data-target="seleccion">
                        <i class="option-icon fas fa-shirt"></i>
                        <span id="selected-seleccion-name">selección</span>
                        <input type="hidden" id="selected-seleccion-id" value="">
                    </div>
                </div>
                
                <button class="publish-btn" disabled>publicar</button>
            </div>
        </div>
        
        <div id="selection-modal" class="selection-modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h3 id="modal-title"></h3>
                <div id="modal-options-container">
                    
                    <div id="mundial-options" class="options-group" style="display: none;">
                        <?php if ($mundiales_list): ?>
                            <?php foreach ($mundiales_list as $m): ?>
                                <button class="selection-item mundial-item" 
                                    data-id="<?= htmlspecialchars($m['id_mundial']) ?>" 
                                    data-name="<?= htmlspecialchars("{$m['nombre']} {$m['año']}") ?>">
                                    <?= htmlspecialchars("{$m['nombre']} {$m['año']}") ?>
                                </button>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No hay mundiales activos.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div id="seleccion-options" class="options-group" style="display: none;">
                        <button class="selection-item seleccion-item" data-id="" data-name="NINGUNA SELECCIÓN">
                            NINGUNA SELECCIÓN (Quitar Selección)
                        </button>
                        <?php if ($selecciones_list): ?>
                            <?php foreach ($selecciones_list as $s): ?>
                                <button class="selection-item seleccion-item" 
                                    data-id="<?= htmlspecialchars($s['id_seleccion']) ?>" 
                                    data-name="<?= htmlspecialchars(ucwords($s['nombre'])) ?>">
                                    <?= htmlspecialchars(ucwords($s['nombre'])) ?>
                                </button>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No hay selecciones activas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="publicaciones-sec">
    <div class="filtros">
        <div class="filtro-grupo">
            <span class="filtro-label">mundial:</span>
            <select class="filtro-select" id="filtro-mundial">
                <option value="0">todos</option>
                <?php if ($mundiales_list): ?>
                    <?php foreach ($mundiales_list as $m): ?>
                        <option value="<?= htmlspecialchars($m['id_mundial']) ?>">
                            <?= htmlspecialchars("{$m['nombre']} {$m['año']}") ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        
        <div class="filtro-grupo">
            <span class="filtro-label">selección:</span>
            <select class="filtro-select" id="filtro-seleccion">
                <option value="0">todas</option>
                <option value="ninguna">NINGUNA SELECCIÓN</option>
                <?php if ($selecciones_list): ?>
                    <?php foreach ($selecciones_list as $s): ?>
                        <option value="<?= htmlspecialchars($s['id_seleccion']) ?>">
                            <?= htmlspecialchars(ucwords($s['nombre'])) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        
        <div class="filtro-grupo">
            <span class="filtro-label">ordenar por:</span>
            <select class="filtro-select" id="filtro-orden">
                <option value="reciente">más recientes</option>
                <option value="popular">más populares</option>
                <option value="comentados">más comentados</option>
            </select>
        </div>
        
        <div class="filtro-grupo">
            <button class="filtro-btn" id="aplicar-filtros">
                aplicar filtros
            </button>
            <button class="filtro-btn limpiar" id="limpiar-filtros">
                limpiar
            </button>
        </div>
    </div>

    <div class="post-carta">
        <div class="post-header">
            <div class="post-avatar">
                <img src="img/profile2.jpg" alt="avatar">
            </div>
            <div class="post-author">naiela dev</div>
            <div class="post-time">hace 3 horas</div>
        </div>
        
        <div class="post-meta">
            <span class="post-meta-item">Jugadas</span>
            <span class="post-meta-item">Qatar 2022</span>
            <span class="post-meta-item">Argentina</span>
        </div>
        
        <div class="post-content">
            <div class="post-image">
                <img src="img/mesii.jpg" alt="jugada de messi">
            </div>
            
            <div class="post-text">
                <h2 class="post-title">la jugada magistral de messi contra holanda</h2>
                <p class="post-description">
                    En el minuto 35 del partido Argentina vs Holanda, Messi realizó una de las jugadas más increíbles del mundial. 
                    Recibió el balón en medio de tres defensores, sorteó a dos con una finta espectacular y filtró un pase milimétrico 
                    a Julián Álvarez que terminó en gol. Una demostración de genialidad pura.
                </p>
            </div>
        </div>
        
        <div class="post-actions">
            <div class="post-action like-btn" id="like-button-1">
                <i class="action-icon fas fa-heart"></i>
                <span class="action-count">243</span>
            </div>
            <div class="post-action comment-btn">
                <i class="action-icon fas fa-comment"></i>
                <span class="action-count">47</span>
            </div>
            <div class="post-action view-btn">
                <i class="action-icon fas fa-eye"></i>
                <span class="action-count">1200</span>
            </div>
        </div>
    </div>

    <div class="post-carta">
        <div class="post-header">
            <div class="post-avatar">
                <img src="img/quatar.jpg" alt="avatar">
            </div>
            <div class="post-author">futbol_stats</div>
            <div class="post-time">hace 1 día</div>
        </div>
        
        <div class="post-meta">
            <span class="post-meta-item">Estadísticas</span>
            <span class="post-meta-item">Qatar 2022</span>
            <span class="post-meta-item">Todos</span>
        </div>
        
        <div class="post-content">
            <div class="post-image">
                <img src="https://images.unsplash.com/photo-1624526267942-ab0c6b3b4d76?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="estadísticas mundial">
            </div>
            
            <div class="post-text">
                <h2 class="post-title">estadísticas sorprendentes de qatar 2022</h2>
                <p class="post-description">
                    El Mundial de Qatar 2022 batió varios récords estadísticos:
                    <br><br>
                    - Mayor cantidad de goles en fase de grupos: 120 goles en 48 partidos
                    <br>
                    - Jugador con más asistencias: Lionel Messi (5 asistencias)
                    <br>
                    - Portero con más paradas: Dominik Livakovic (25 paradas)
                    <br>
                    - Equipo con mayor posesión de balón: España (67% promedio)
                    <br><br>
                    Un torneo lleno de datos interesantes que reflejan la evolución del fútbol moderno.
                </p>
            </div>
        </div>
        
        <div class="post-actions">
            <div class="post-action like-btn" id="like-button-2">
                <i class="action-icon fas fa-heart"></i>
                <span class="action-count">187</span>
            </div>
            <div class="post-action comment-btn">
                <i class="action-icon fas fa-comment"></i>
                <span class="action-count">32</span>
            </div>
            <div class="post-action view-btn">
                <i class="action-icon fas fa-eye"></i>
                <span class="action-count">980</span>
            </div>
        </div>
    </div>

    <div class="post-carta">
        <div class="post-header">
            <div class="post-avatar">
                <img src="img/portugal.jpg" alt="avatar">
            </div>
            <div class="post-author">debate_futbolero</div>
            <div class="post-time">hace 2 días</div>
        </div>
        
        <div class="post-meta">
            <span class="post-meta-item">Polémicas</span>
            <span class="post-meta-item">Qatar 2022</span>
            <span class="post-meta-item">Portugal</span>
        </div>
        
        <div class="post-content">
            <div class="post-image">
                <img src="img/back.jpg" alt="polémica penalti">
            </div>
            
            <div class="post-text">
                <h2 class="post-title">la polémica decisión arbitral en portugal vs uruguay</h2>
                <p class="post-description">
                    El partido entre Portugal y Uruguay estuvo marcado por una decisión arbitral controversial en el minuto 72. 
                    El árbitro señaló penal a favor de Portugal por una mano de Giménez dentro del área, tras revisar el VAR.
                    <br><br>
                    Muchos expertos consideran que fue una decisión excesivamente rigurosa, ya que el defensa uruguayo tenía 
                    los brazos pegados al cuerpo y el balón impactó desde muy corta distancia. ¿Penal correcto o error arbitral?
                </p>
            </div>
        </div>
        
        <div class="post-actions">
            <div class="post-action like-btn" id="like-button-3">
                <i class="action-icon fas fa-heart"></i>
                <span class="action-count">321</span>
            </div>
            <div class="post-action comment-btn">
                <i class="action-icon fas fa-comment"></i>
                <span class="action-count">89</span>
            </div>
            <div class="post-action view-btn">
                <i class="action-icon fas fa-eye"></i>
                <span class="action-count">1500</span>
            </div>
        </div>
    </div>
</section>

<script src="js/publicaciones.js"></script>
</body>
</html>