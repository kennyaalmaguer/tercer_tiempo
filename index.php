<?php
session_start();

// Conexión a la base de datos
$servername = "127.0.0.1";
$username = "root"; // Cambiar si es necesario
$password = ""; // Cambiar si es necesario
$dbname = "tercer_tiempo";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta para obtener los mundiales con estadísticas
$sql = "
    SELECT 
        m.id_mundial,
        m.nombre,
        m.año,
        m.pais_sede,
        m.descripcion,
        m.imagen_representativa,
        m.logo,
        COUNT(DISTINCT p.id_publicacion) as publicaciones_count,
        COUNT(DISTINCT l.id_like) as likes_count,
        COUNT(DISTINCT v.id_vista) as vistas_count,
        COUNT(DISTINCT c.id_comentario) as comentarios_count
    FROM mundiales m
    LEFT JOIN publicaciones p ON m.id_mundial = p.id_mundial AND p.estado = 'aprobado'
    LEFT JOIN likes l ON p.id_publicacion = l.id_publicacion
    LEFT JOIN vistas v ON p.id_publicacion = v.id_publicacion
    LEFT JOIN comentarios c ON p.id_publicacion = c.id_publicacion
    WHERE m.activo = 1
    GROUP BY m.id_mundial
    ORDER BY m.año DESC
";

$result = $conn->query($sql);
$mundiales = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Convertir BLOB a base64 para las imágenes
        $imagen_base64 = null;
        $logo_base64 = null;
        
        if ($row['imagen_representativa']) {
            $imagen_base64 = 'data:image/jpeg;base64,' . base64_encode($row['imagen_representativa']);
        }
        
        if ($row['logo']) {
            $logo_base64 = 'data:image/jpeg;base64,' . base64_encode($row['logo']);
        }
        
        $mundiales[] = [
            'id' => $row['id_mundial'],
            'nombre' => strtolower($row['nombre']),
            'año' => $row['año'],
            'pais' => strtolower($row['pais_sede']),
            'imagen' => $imagen_base64 ?: 'img/default-mundial.jpg',
            'logo' => $logo_base64 ?: 'img/default-logo.jpg',
            'descripcion' => $row['descripcion'],
            'likes' => $row['likes_count'],
            'comentarios' => $row['comentarios_count'],
            'vistas' => $row['vistas_count'],
            'publicaciones' => $row['publicaciones_count']
        ];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/LOGO.png" type="image/x-icon">
    <title>Tercer Tiempo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Estilos para la sección de Mundiales */
    .mundiales-section {
        background: #0066b3;
        padding: 60px 40px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .mundiales-section::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('img/f2.png');
        background-size: cover;
        opacity: 0.1;
        pointer-events: none;
    }

    .mundiales-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .filtros-mundiales {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filtro-btn {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 8px 16px;
        border-radius: 20px;
        cursor: pointer;
        font-family: 'The Youth', sans-serif;
        transition: all 0.3s;
        text-transform: lowercase;
        font-size: 14px;
    }

    .filtro-btn.active,
    .filtro-btn:hover {
        background: #FFD700;
        color: #0066b3;
    }

    .mundiales-container {
        position: relative;
        overflow: hidden;
        padding: 10px 0;
    }

    .mundiales-slider {
        display: flex;
        transition: transform 0.5s ease;
        gap: 20px;
    }

    .mundial-card {
        flex: 0 0 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
    }

    .mundial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }

    .mundial-imagen {
        width: 100%;
        height: 180px;
        overflow: hidden;
        position: relative;
    }

    .mundial-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .mundial-card:hover .mundial-imagen img {
        transform: scale(1.05);
    }

    .mundial-logo {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 50px;
        height: 50px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }

    .mundial-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .mundial-info {
        padding: 20px;
    }

    .mundial-nombre {
        font-family: 'The Youth', sans-serif;
        font-size: 20px;
        margin-bottom: 5px;
        color: #FFD700;
        text-transform: lowercase;
    }

    .mundial-detalles {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        font-size: 14px;
        color: #cccccc;
    }

    .mundial-descripcion {
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .mundial-stats {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #cccccc;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        padding-top: 10px;
        margin-bottom: 10px;
    }

    .stat {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .publicaciones-count {
        background: rgba(255, 215, 0, 0.2);
        border: 1px solid rgba(255, 215, 0, 0.4);
        border-radius: 15px;
        padding: 5px 12px;
        font-size: 12px;
        color: #FFD700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 10px;
    }

    .ver-mas-btn {
        background: #FFD700;
        color: #0066b3;
        border: none;
        padding: 8px 15px;
        border-radius: 20px;
        font-family: 'The Youth', sans-serif;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
        text-transform: lowercase;
        font-size: 14px;
    }

    .ver-mas-btn:hover {
        background: white;
    }

    .slider-controls {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .slider-btn {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .slider-btn:hover {
        background: #FFD700;
        color: #0066b3;
    }

    .slider-dots {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .slider-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        cursor: pointer;
        transition: all 0.3s;
    }

    .slider-dot.active {
        background: #FFD700;
    }

    @media (max-width: 768px) {
        .mundiales-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .filtros-mundiales {
            width: 100%;
            justify-content: center;
        }
        
        .mundial-card {
            flex: 0 0 280px;
        }

        .mundial-nombre {
            font-family: 'The Youth', sans-serif;
            font-size: 18px;
            margin-bottom: 5px;
            color: #FFD700;
            text-transform: lowercase;
        }
    }

    </style>
</head>
<body>

    <div class="hero">
        <header>
            <div class="nav-item">
                <p>Tt</p>
            </div>

            <nav>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Mostrar cuando el usuario ESTÁ logueado -->
                    <a href="publicaciones.php">publicaciones</a>
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
        
        <div class="hero-text">
            <p>Tercer tiempo</p>
        </div>
    </div>

    <div class="azul-section">
        <div class="mundial-content">
            <h2 class="mundial-title">mundial de fútbol 2026</h2>
            
            <p class="mundial-subtitle">Vive la emoción del torneo más grande del planeta. 48 selecciones, 3 países anfitriones y una pasión que une al mundo.</p>
            
            <div class="mundial-stats">
                <div class="stat-item">
                    <div class="stat-number">48</div>
                    <div class="stat-label">equipos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">80</div>
                    <div class="stat-label">partidos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">3</div>
                    <div class="stat-label">países</div>
                </div>
            </div>
            
            <button class="mundial-cta">proximamente</button>
        </div>
    </div>

    <!-- MUNDIALES -->
    <div class="mundiales-section">
        <div class="mundiales-header">
            <h2 class="section-title">mundiales históricos</h2>
            <div class="filtros-mundiales">
                <button class="filtro-btn active" data-filtro="todos">todos</button>
                <button class="filtro-btn" data-filtro="recientes">más recientes</button>
                <button class="filtro-btn" data-filtro="populares">más populares</button>
                <button class="filtro-btn" data-filtro="comentados">más comentados</button>
                <button class="filtro-btn" data-filtro="publicaciones">más publicaciones</button>
            </div>
        </div>
        
        <div class="mundiales-container">
            <div class="mundiales-slider" id="mundiales-slider">
                <!-- Tarjetas de mundiales generadas dinámicamente desde PHP/JS -->
            </div>
        </div>
        
        <div class="slider-controls">
            <button class="slider-btn" id="prev-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="slider-dots" id="slider-dots">
                <!-- Puntos del slider generados dinámicamente -->
            </div>
            <button class="slider-btn" id="next-btn">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <div class="footer-logo">Tercer Tiempo</div>
            
            <div class="footer-links">
                <a href="#">Inicio</a>
                <a href="#">Publicaciones</a>
                <a href="#">Categorías</a>
                <a href="#">Mundial 2026</a>
            </div>
            
            <div class="footer-social">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
            </div>
            
            <div class="footer-copyright">
                © 2023 Tercer Tiempo. Todos los derechos reservados.
            </div>
        </div>
    </div>

    <script>
        // Convertir los datos de PHP a JavaScript
        const mundiales = <?php echo json_encode($mundiales); ?>;

        // Si no hay mundiales en la base de datos, usar datos de ejemplo
        const mundialesData = mundiales.length > 0 ? mundiales : [
            {
                id: 1,
                nombre: "qatar 2022",
                año: 2022,
                pais: "qatar",
                imagen: "img/default-mundial.jpg",
                logo: "img/default-logo.jpg",
                descripcion: "El primer mundial celebrado en el mundo árabe, con Argentina como campeona tras una emocionante final contra Francia.",
                likes: 245,
                comentarios: 78,
                vistas: 1200,
                publicaciones: 42
            },
            {
                id: 2,
                nombre: "rusia 2018",
                año: 2018,
                pais: "rusia",
                imagen: "img/default-mundial.jpg",
                logo: "img/default-logo.jpg",
                descripcion: "Francia se coronó campeón por segunda vez en su historia tras vencer a Croacia en la final.",
                likes: 189,
                comentarios: 56,
                vistas: 980,
                publicaciones: 35
            }
        ];

        // Variables para el slider
        let currentSlide = 0;
        const mundialesPorSlide = 3;
        const totalSlides = Math.ceil(mundialesData.length / mundialesPorSlide);

        // Elementos del DOM
        const slider = document.getElementById('mundiales-slider');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const sliderDots = document.getElementById('slider-dots');
        const filtros = document.querySelectorAll('.filtro-btn');

        // Función para renderizar las tarjetas de mundiales
        function renderMundiales(mundialesData) {
            slider.innerHTML = '';
            
            mundialesData.forEach(mundial => {
                const card = document.createElement('div');
                card.className = 'mundial-card';
                card.innerHTML = `
                    <div class="mundial-imagen">
                        <img src="${mundial.imagen}" alt="${mundial.nombre}" onerror="this.src='img/default-mundial.jpg'">
                        <div class="mundial-logo">
                            <img src="${mundial.logo}" alt="Logo ${mundial.nombre}" onerror="this.src='img/default-logo.jpg'">
                        </div>
                    </div>
                    <div class="mundial-info">
                        <h3 class="mundial-nombre">${mundial.nombre}</h3>
                        <div class="mundial-detalles">
                            <span>${mundial.año}</span>
                            <span>•</span>
                            <span>${mundial.pais}</span>
                        </div>
                        <div class="publicaciones-count">
                            <i class="fas fa-file-alt"></i>
                            <span>${mundial.publicaciones} publicaciones</span>
                        </div>
                        <p class="mundial-descripcion">${mundial.descripcion}</p>
                        <div class="mundial-stats">
                            <div class="stat">
                                <i class="fas fa-heart"></i>
                                <span>${mundial.likes}</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-comment"></i>
                                <span>${mundial.comentarios}</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-eye"></i>
                                <span>${mundial.vistas}</span>
                            </div>
                        </div>
                        <button class="ver-mas-btn" data-id="${mundial.id}">ver más</button>
                    </div>
                `;
                slider.appendChild(card);
            });
            
            // Actualizar puntos del slider
            updateSliderDots();
        }

        // Función para actualizar los puntos del slider
        function updateSliderDots() {
            sliderDots.innerHTML = '';
            
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('div');
                dot.className = `slider-dot ${i === currentSlide ? 'active' : ''}`;
                dot.addEventListener('click', () => goToSlide(i));
                sliderDots.appendChild(dot);
            }
        }

        // Función para ir a un slide específico
        function goToSlide(slideIndex) {
            currentSlide = slideIndex;
            const translateX = -currentSlide * 100;
            slider.style.transform = `translateX(${translateX}%)`;
            updateSliderDots();
        }

        // Función para filtrar mundiales
        function filtrarMundiales(filtro) {
            let mundialesFiltrados = [...mundialesData];
            
            switch(filtro) {
                case 'recientes':
                    mundialesFiltrados.sort((a, b) => b.año - a.año);
                    break;
                case 'populares':
                    mundialesFiltrados.sort((a, b) => b.likes - a.likes);
                    break;
                case 'comentados':
                    mundialesFiltrados.sort((a, b) => b.comentarios - a.comentarios);
                    break;
                case 'publicaciones':
                    mundialesFiltrados.sort((a, b) => b.publicaciones - a.publicaciones);
                    break;
                default:
                    // 'todos' - no hacer nada
                    break;
            }
            
            renderMundiales(mundialesFiltrados);
            currentSlide = 0;
            goToSlide(0);
        }

        // Event Listeners
        prevBtn.addEventListener('click', () => {
            if (currentSlide > 0) {
                goToSlide(currentSlide - 1);
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentSlide < totalSlides - 1) {
                goToSlide(currentSlide + 1);
            }
        });

        filtros.forEach(filtro => {
            filtro.addEventListener('click', () => {
                // Remover clase active de todos los filtros
                filtros.forEach(f => f.classList.remove('active'));
                // Agregar clase active al filtro clickeado
                filtro.classList.add('active');
                // Aplicar filtro
                filtrarMundiales(filtro.dataset.filtro);
            });
        });

        // Inicializar
        renderMundiales(mundialesData);
        goToSlide(0);
    </script>
</body>
</html>