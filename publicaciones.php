<?php
session_start();
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
            <!-- Mostrar cuando el usuario ESTÁ logueado -->
            <a href="index.php">inicio</a>
            <a href="perfil.php">perfil</a>
            <a href="logout.php">cerrar sesión</a>
        <?php else: ?>
            <!-- Mostrar cuando el usuario NO está logueado -->
            <a href="publicaciones.php">publicaciones</a>
            <a href="registro.php">registro</a>
            <a href="login.php">log in</a>
        <?php endif; ?>
    </nav>
</header>

<!-- segunda sección -->
<section class="segunda-seccion">
    <!-- espacio para otra sección -->
</section>

<!-- sección de categorías -->
<section class="categorias-sec">
    <h2 class="categorias-titulo">categorías</h2>
    <div class="categorias-container">
        <button class="categoria-btn active">todas</button>
        <button class="categoria-btn">jugadas</button>
        <button class="categoria-btn">entrevistas</button>
        <button class="categoria-btn">partidos</button>
        <button class="categoria-btn">estadísticas</button>
        <button class="categoria-btn">asistentes</button>
        <button class="categoria-btn">incidentes</button>
        <button class="categoria-btn">polémicas</button>
        <button class="categoria-btn">sedes</button>
        <button class="categoria-btn">cultura</button>
    </div>
</section>

<!-- sección para crear publicaciones -->
<section class="create-post-sec">
    <div class="create-post-container">
        <div class="create-post-box">
            <div class="create-post-header">
                <div class="create-post-avatar">
                    <img src="img/profile2.jpg" alt="avatar">
                </div>
                <div class="create-post-input">
                    <textarea placeholder="¿qué estás pensando, naiela?"></textarea>
                </div>
            </div>
            
            <div class="image-preview" id="image-preview">
                <img id="preview-img" src="" alt="vista previa">
                <button class="remove-image" onclick="removeImage()">×</button>
            </div>
            
            <div class="create-post-options">
                <div class="post-options">
                    <label for="image-upload" class="option-btn">
                        <i class="option-icon fas fa-image"></i>
                        <span>foto</span>
                    </label>
                    <input type="file" id="image-upload" accept="image/*" style="display: none;">
                    
                    <label for="video-upload" class="option-btn">
                        <i class="option-icon fas fa-video"></i>
                        <span>video</span>
                    </label>
                    <input type="file" id="video-upload" accept="video/*" style="display: none;">
                    
                    <div class="option-btn" onclick="alert('Selecciona una categoría')">
                        <i class="option-icon fas fa-tag"></i>
                        <span>categoría</span>
                    </div>

                    <div class="option-btn" onclick="alert('Selecciona un mundial')">
                        <i class="option-icon fas fa-trophy"></i>
                        <span>mundial</span>
                    </div>
                </div>
                
                <button class="publish-btn">publicar</button>
            </div>
        </div>
    </div>
</section>

<!-- sección de publicaciones -->
<section class="publicaciones-sec">
    <div class="filtros">
        <div class="filtro-grupo">
            <span class="filtro-label">mundial:</span>
            <select class="filtro-select">
                <option>todos</option>
                <option>Qatar 2022</option>
                <option>Rusia 2018</option>
                <option>Brasil 2014</option>
                <option>Sudáfrica 2010</option>
                <option>Alemania 2006</option>
            </select>
        </div>
        
        <div class="filtro-grupo">
            <span class="filtro-label">selección:</span>
            <select class="filtro-select">
                <option>todas</option>
                <option>Argentina</option>
                <option>Brasil</option>
                <option>Francia</option>
                <option>España</option>
                <option>Alemania</option>
            </select>
        </div>
        
        <div class="filtro-grupo">
            <span class="filtro-label">ordenar por:</span>
            <select class="filtro-select">
                <option>más recientes</option>
                <option>más populares</option>
                <option>más comentados</option>
            </select>
        </div>
    </div>

    <!-- Publicación 1 - Jugadas -->
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
            <div class="post-action share-btn">
                <i class="action-icon fas fa-share-alt"></i>
                <span class="action-count">compartir</span>
            </div>
        </div>
    </div>

    <!-- Publicación 2 - Estadísticas -->
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
            <div class="post-action share-btn">
                <i class="action-icon fas fa-share-alt"></i>
                <span class="action-count">compartir</span>
            </div>
        </div>
    </div>

    <!-- Publicación 3 - Polémicas -->
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
            <div class="post-action share-btn">
                <i class="action-icon fas fa-share-alt"></i>
                <span class="action-count">compartir</span>
            </div>
        </div>
    </div>
</section>

<script src="js/publicaciones.js"></script>
</body>
</html>