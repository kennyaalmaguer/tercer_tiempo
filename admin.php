<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/LOGO.png" type="image/x-icon">
    <title>Panel de Administración - Tercer Tiempo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos base del admin */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 250px;
            background: #003366;
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .admin-logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            font-family: 'The Youth', sans-serif;
            font-size: 24px;
            color: #FFD700;
        }

        .admin-nav {
            padding: 20px 0;
        }

        .admin-nav-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .admin-nav-item:hover, 
        .admin-nav-item.active {
            background: rgba(255,255,255,0.1);
            border-left-color: #FFD700;
        }

        .admin-nav-item i {
            width: 20px;
            text-align: center;
        }

        /* Contenido principal */
        .admin-main {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }

        .admin-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-title {
            font-family: 'The Youth', sans-serif;
            font-size: 28px;
            color: #003366;
            text-transform: lowercase;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #FFD700;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #003366;
            font-weight: bold;
        }

        /* Tarjetas de estadísticas */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #003366;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
        }

        /* Secciones de contenido */
        .admin-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .section-title {
            font-family: 'The Youth', sans-serif;
            font-size: 22px;
            color: #003366;
            text-transform: lowercase;
        }

        /* Botones */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'The Youth', sans-serif;
            text-transform: lowercase;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-primary {
            background: #FFD700;
            color: #003366;
        }

        .btn-primary:hover {
            background: #e6c200;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #003366;
            color: #003366;
        }

        .btn-outline:hover {
            background: #003366;
            color: white;
        }

        /* Tablas */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th,
        .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #003366;
            text-transform: lowercase;
            font-family: 'The Youth', sans-serif;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .table-actions {
            display: flex;
            gap: 5px;
        }

        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }

        /* Formularios */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #003366;
            text-transform: lowercase;
            font-family: 'The Youth', sans-serif;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #003366;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .modal-title {
            font-family: 'The Youth', sans-serif;
            font-size: 20px;
            color: #003366;
            text-transform: lowercase;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 20px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Estados */
        .status-pending {
            background: #fff3cd;
            color: #856404;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
        }

        .status-approved {
            background: #d1ecf1;
            color: #0c5460;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .form-row {
                flex-direction: column;
            }
            
            .table {
                display: block;
                overflow-x: auto;
            }
        }

        /* Fuente personalizada */
        @font-face {
            font-family: 'The Youth';
            src: url('fonts/TheYouth.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
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
            <a href="#" class="admin-nav-item">
                <i class="fas fa-cog"></i>
                <span>configuración</span>
            </a>
            <a href="index.php" class="admin-nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>salir</span>
            </a>
        </nav>
    </div>

    <!-- Contenido principal -->
    <div class="admin-main">
        <header class="admin-header">
            <h1 class="admin-title" id="section-title">panel de administración</h1>
            <div class="admin-user">
                <div class="user-avatar">A</div>
                <span>administrador</span>
            </div>
        </header>

        <!-- Dashboard -->
        <div id="dashboard" class="admin-section active">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">8</div>
                    <div class="stat-label">mundiales activos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">42</div>
                    <div class="stat-label">publicaciones pendientes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">156</div>
                    <div class="stat-label">total publicaciones</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">12</div>
                    <div class="stat-label">categorías</div>
                </div>
            </div>

            <div class="section-header">
                <h2 class="section-title">actividad reciente</h2>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>fecha</th>
                        <th>acción</th>
                        <th>usuario</th>
                        <th>detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2023-11-15 14:30</td>
                        <td>Publicación aprobada</td>
                        <td>admin</td>
                        <td>"Mejor jugada del partido - Argentina vs Francia"</td>
                    </tr>
                    <tr>
                        <td>2023-11-15 13:15</td>
                        <td>Mundial creado</td>
                        <td>admin</td>
                        <td>Qatar 2022</td>
                    </tr>
                    <tr>
                        <td>2023-11-15 11:45</td>
                        <td>Usuario registrado</td>
                        <td>Sistema</td>
                        <td>nuevo usuario: fan_futbol</td>
                    </tr>
                    <tr>
                        <td>2023-11-14 16:20</td>
                        <td>Categoría creada</td>
                        <td>admin</td>
                        <td>"Goles espectaculares"</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Gestión de Mundiales -->
        <div id="mundiales" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">gestión de mundiales</h2>
                <button class="btn btn-primary" id="add-mundial">
                    <i class="fas fa-plus"></i> agregar mundial
                </button>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>nombre</th>
                        <th>año</th>
                        <th>país</th>
                        <th>publicaciones</th>
                        <th>estado</th>
                        <th>acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>qatar 2022</td>
                        <td>2022</td>
                        <td>qatar</td>
                        <td>42</td>
                        <td><span class="status-approved">activo</span></td>
                        <td class="table-actions">
                            <button class="action-btn btn-primary">editar</button>
                            <button class="action-btn btn-danger">eliminar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>rusia 2018</td>
                        <td>2018</td>
                        <td>rusia</td>
                        <td>35</td>
                        <td><span class="status-approved">activo</span></td>
                        <td class="table-actions">
                            <button class="action-btn btn-primary">editar</button>
                            <button class="action-btn btn-danger">eliminar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>brasil 2014</td>
                        <td>2014</td>
                        <td>brasil</td>
                        <td>28</td>
                        <td><span class="status-approved">activo</span></td>
                        <td class="table-actions">
                            <button class="action-btn btn-primary">editar</button>
                            <button class="action-btn btn-danger">eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Gestión de Categorías -->
        <div id="categorias" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">gestión de categorías</h2>
                <button class="btn btn-primary" id="add-category">
                    <i class="fas fa-plus"></i> agregar categoría
                </button>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>nombre</th>
                        <th>descripción</th>
                        <th>publicaciones</th>
                        <th>acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>goles espectaculares</td>
                        <td>Los goles más impresionantes de cada mundial</td>
                        <td>24</td>
                        <td class="table-actions">
                            <button class="action-btn btn-primary">editar</button>
                            <button class="action-btn btn-danger">eliminar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>jugadas históricas</td>
                        <td>Momentos que marcaron la historia del fútbol</td>
                        <td>18</td>
                        <td class="table-actions">
                            <button class="action-btn btn-primary">editar</button>
                            <button class="action-btn btn-danger">eliminar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>entrevistas</td>
                        <td>Conversaciones con jugadores y entrenadores</td>
                        <td>15</td>
                        <td class="table-actions">
                            <button class="action-btn btn-primary">editar</button>
                            <button class="action-btn btn-danger">eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Aprobación de Publicaciones -->
        <div id="publicaciones" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">publicaciones pendientes</h2>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>título</th>
                        <th>usuario</th>
                        <th>mundial</th>
                        <th>categoría</th>
                        <th>fecha</th>
                        <th>estado</th>
                        <th>acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>El gol de Maradona contra Inglaterra</td>
                        <td>fan_futbol</td>
                        <td>México 1986</td>
                        <td>Goles espectaculares</td>
                        <td>2023-11-15</td>
                        <td><span class="status-pending">pendiente</span></td>
                        <td class="table-actions">
                            <button class="action-btn btn-success">aprobar</button>
                            <button class="action-btn btn-danger">rechazar</button>
                            <button class="action-btn btn-outline">ver</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Entrevista con Messi después del triunfo</td>
                        <td>periodista_deportivo</td>
                        <td>Qatar 2022</td>
                        <td>Entrevistas</td>
                        <td>2023-11-14</td>
                        <td><span class="status-pending">pendiente</span></td>
                        <td class="table-actions">
                            <button class="action-btn btn-success">aprobar</button>
                            <button class="action-btn btn-danger">rechazar</button>
                            <button class="action-btn btn-outline">ver</button>
                        </td>
                    </tr>
                    <tr>
                        <td>La mano de Dios - Análisis técnico</td>
                        <td>analista_futbol</td>
                        <td>México 1986</td>
                        <td>Jugadas históricas</td>
                        <td>2023-11-13</td>
                        <td><span class="status-pending">pendiente</span></td>
                        <td class="table-actions">
                            <button class="action-btn btn-success">aprobar</button>
                            <button class="action-btn btn-danger">rechazar</button>
                            <button class="action-btn btn-outline">ver</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Gestión de Usuarios -->
        <div id="usuarios" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">gestión de usuarios</h2>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>usuario</th>
                        <th>email</th>
                        <th>rol</th>
                        <th>fecha registro</th>
                        <th>publicaciones</th>
                        <th>estado</th>
                        <th>acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>fan_futbol</td>
                        <td>fan@ejemplo.com</td>
                        <td>usuario</td>
                        <td>2023-10-15</td>
                        <td>12</td>
                        <td><span class="status-approved">activo</span></td>
                        <td class="table-actions">
                            <button class="action-btn btn-primary">editar</button>
                            <button class="action-btn btn-danger">bloquear</button>
                        </td>
                    </tr>
                    <tr>
                        <td>periodista_deportivo</td>
                        <td>periodista@ejemplo.com</td>
                        <td>usuario</td>
                        <td>2023-09-22</td>
                        <td>8</td>
                        <td><span class="status-approved">activo</span></td>
                        <td class="table-actions">
                            <button class="action-btn btn-primary">editar</button>
                            <button class="action-btn btn-danger">bloquear</button>
                        </td>
                    </tr>
                    <tr>
                        <td>analista_futbol</td>
                        <td>analista@ejemplo.com</td>
                        <td>usuario</td>
                        <td>2023-11-05</td>
                        <td>5</td>
                        <td><span class="status-approved">activo</span></td>
                        <td class="table-actions">
                            <button class="action-btn btn-primary">editar</button>
                            <button class="action-btn btn-danger">bloquear</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para agregar mundial -->
    <div id="modal-mundial" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">agregar nuevo mundial</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-mundial">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">nombre del mundial</label>
                            <input type="text" class="form-control" placeholder="Ej: qatar 2022" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">año</label>
                            <input type="number" class="form-control" placeholder="2022" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">país sede</label>
                        <input type="text" class="form-control" placeholder="Ej: qatar" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">descripción</label>
                        <textarea class="form-control" rows="3" placeholder="Breve descripción del mundial..."></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">imagen representativa</label>
                            <input type="file" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label class="form-label">logotipo</label>
                            <input type="file" class="form-control" accept="image/*">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" id="cancel-mundial">cancelar</button>
                <button class="btn btn-primary" id="save-mundial">guardar mundial</button>
            </div>
        </div>
    </div>

    <script>
        // Navegación entre secciones
        document.querySelectorAll('.admin-nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
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
            });
        });

        // Cerrar modal al hacer clic fuera del contenido
        window.addEventListener('click', (e) => {
            if (e.target === modalMundial) {
                modalMundial.style.display = 'none';
            }
        });

        // Guardar mundial
        document.getElementById('save-mundial').addEventListener('click', () => {
            // Aquí iría la lógica para guardar en la base de datos
            alert('Mundial guardado correctamente');
            modalMundial.style.display = 'none';
            document.getElementById('form-mundial').reset();
        });

        // Ejemplo de funcionalidad para aprobar/rechazar publicaciones
        document.querySelectorAll('.action-btn.btn-success').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const title = row.querySelector('td:first-child').textContent;
                if (confirm(`¿Estás seguro de que quieres aprobar "${title}"?`)) {
                    row.querySelector('.status-pending').className = 'status-approved';
                    row.querySelector('.status-pending').textContent = 'aprobado';
                    // Aquí iría la lógica para actualizar en la base de datos
                }
            });
        });

        document.querySelectorAll('.action-btn.btn-danger').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const title = row.querySelector('td:first-child').textContent;
                if (confirm(`¿Estás seguro de que quieres rechazar "${title}"?`)) {
                    row.querySelector('.status-pending').className = 'status-rejected';
                    row.querySelector('.status-pending').textContent = 'rechazado';
                    // Aquí iría la lógica para actualizar en la base de datos
                }
            });
        });
    </script>
</body>
</html>