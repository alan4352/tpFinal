<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
$servername = "localhost"; // Cambia esto si es necesario
$username = "root"; // Cambia esto por tu usuario de base de datos
$password = ""; // Cambia esto por tu contraseña de base de datos
$dbname = "cfp61"; // Nombre de la base de datos

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aquí manejas la creación y actualización de cursos
    if (isset($_POST['update'])) {
        // Manejo de actualización
        $courseId = $_POST['course_id'];
        $name = $_POST['name'];
        $descriptiom = $_POST['descriptiom'];
        $image_url = $_POST['image_url'];

        $stmt = $conn->prepare("UPDATE cursos SET name=?, descriptiom=?, image=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $descriptiom, $image_url, $courseId);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Curso actualizado con éxito.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el curso.']);
        }
        $stmt->close();
    } else {
        // Manejo de creación
        $name = $_POST['name'];
        $descriptiom = $_POST['descriptiom'];
        $image_url = $_POST['image_url'];

        $stmt = $conn->prepare("INSERT INTO cursos (name, descriptiom, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $descriptiom, $image_url);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Curso creado con éxito.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
    }
    $conn->close();
    exit; // Asegúrate de salir después de manejar la solicitud POST
}

// Manejar solicitudes DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    if (isset($data['course_id'])) {
        $courseId = $data['course_id'];

        $stmt = $conn->prepare("DELETE FROM cursos WHERE id=?");
        $stmt->bind_param("i", $courseId);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Curso eliminado con éxito.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el curso.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID del curso no proporcionado.']);
    }
    $conn->close();
    exit; // Asegúrate de salir después de manejar la solicitud DELETE
}

// Manejar solicitudes GET
$cursos = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM cursos");
    if ($result === false) {
        die("Error en la consulta: " . $conn->error);
    }
    while ($row = $result->fetch_assoc()) {
        $cursos[] = $row;
    }
    $conn->close();
    
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Centro Formación Profesional N° 61</title>
</head>
<body>
    <div class="bg-info text-white text-center py-4">
        <h1>Centro Formación Profesional N° 61</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Inicio</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="./Inscripcion/inscripciones.php">Inscripciones</a></li>
                    <li class="nav-item"><a class="nav-link" href="#donde-estamos">¿Dónde estamos?</a></li>
                    <li class="nav-item"><a class="nav-link" href="#trayectos">Trayectos Formativos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#adminLoginModal">Login Administrador</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Modal de Login de Administrador -->
    <div class="modal fade" id="adminLoginModal" tabindex="-1" role="dialog" aria-labelledby="adminLoginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminLoginModalLabel">Login Administrador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="admin-login-form">
                        <div class="form-group">
                            <label for="admin-username">Usuario:</label>
                            <input type="text" class="form-control" id="admin-username" required>
                        </div>
                        <div class="form-group">
                            <label for="admin-password">Contraseña:</label>
                            <input type="password" class="form-control" id="admin-password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <section id="quienes-somos">
            <h2>¿Quiénes somos?</h2>
            <p>El CENTRO FORMACIÓN PROFESIONAL N° 61 depende de la Dirección de Educación Técnico Profesional del Consejo General de Educación. Funciona en La Criolla desde 2014 y tiene un anexo en Colonia Ayuí.</p>
            <p>Brindamos trayectos de formación profesional y capacitación laboral para una rápida inserción en el mercado socioproductivo local y regional.</p>
        </section>

        <section id="donde-estamos" class="my-5">
            <h2>¿Dónde estamos?</h2>
            <p>Rio Bermejo N°278, La Criolla, Dpto Concordia. Instalaciones del Club Juan B. Alberdi.</p>
        </section>

        <section id="trayectos" class="my-5">
            <h2>Trayectos Formativos</h2>
            <div class="row" id="courses-container">
                <?php foreach ($cursos as $course): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="<?= $course['image'] ?>" class="card-img-top" alt="<?= $course['name'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $course['name'] ?></h5>
                                <p class="card-text"><?= $course['descriptiom'] ?></p>
                                <button class="btn btn-warning" id=(<?= $course['id'] ?>)">Editar</button>
                                <button class="btn btn-danger" onclick="deleteCourse(<?= $course['id'] ?>)">Eliminar</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="btn btn-primary" id="show-create-course" style="display: none;" onclick="showCreateCoursePanel()">Crear Curso</button>
        </section>

        <section id="create-course" class="my-5" style="display: none;">
            <h2>Crear/Actualizar Curso</h2>
            <form id="create-course-form">
                <div class="form-group">
                    <label for="course-name">Nombre del Curso:</label>
                    <input type="text" class="form-control" id="course-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="course-descriptiom">Descripción del Curso:</label>
                    <textarea class="form-control" id="course-descriptiom" name="descriptiom" required></textarea>
                </div>
                <div class="form-group">
                    <label for="course-image-url">URL de la Imagen del Curso:</label>
                    <input type="url" class="form-control" id="course-image-url" name="image_url" required>
                </div>
                <button type="submit" class="btn btn-success" id="submit-button">Crear Curso</button>
                <button type="button" class="btn btn-warning" id="update-button" style="display: none;" onclick="updateCourse()">Actualizar Curso</button>
                <button type="button" class="btn btn-secondary" id="cancel-button" style="display: none;" onclick="cancelEdit()">Cancelar</button>
            </form>
        </section>
    </div>

    <script>
    let currentEditIndex = -1;
    let courses = [];
    let isAdmin = false; // Estado de autenticación

    document.getElementById('admin-login-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const username = document.getElementById('admin-username').value;
        const password = document.getElementById('admin-password').value;

        // Simulación de autenticación
        if (username === 'admin' && password === 'password') {
            isAdmin = true;
            alert('Inicio de sesión exitoso');
            document.getElementById('show-create-course').style.display = 'block';
            fetchCourses();
            $('#adminLoginModal').modal('hide');
        } else {
            alert('Usuario o contraseña incorrectos');
        }
    });

    document.getElementById('create-course-form').addEventListener('submit', function (event) {
        event.preventDefault(); // Evita el envío normal del formulario
        if (!isAdmin) {
            alert('No tienes permiso para crear cursos');
            return;
        }
        
        const courseName = document.getElementById('course-name').value;
        const courseDescription = document.getElementById('course-descriptiom').value;
        const courseImageUrl = document.getElementById('course-image-url').value;

        const formData = new FormData();
        formData.append('name', courseName);
        formData.append('descriptiom', courseDescription);
        formData.append('image_url', courseImageUrl);

        fetch('index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                fetchCourses(); // Cargar los cursos nuevamente
                document.getElementById('create-course-form').reset(); // Limpiar el formulario
                hideCreateCoursePanel(); // Ocultar el panel de creación
            }
        })
        .catch(error => console.error('Error al enviar el formulario:', error));
    });

    function fetchCourses() {
        fetch('index.php')
            .then(response => response.json())
            .then(data => {
                courses = data;
                renderCourses();
            })
            .catch(error => console.error('Error al cargar los cursos:', error));
    }

    function renderCourses() {
        const container = document.getElementById('courses-container');
        container.innerHTML = '';
        courses.forEach((course, index) => {
            const courseCard = document.createElement('div');
            courseCard.className = 'col-md-4';
            courseCard.innerHTML = 
                <div class="card mb-4">
                    <img src="${course.image_url}" class="card-img-top" alt="${course.name}">
                    <div class="card-body">
                        <h5 class="card-title">${course.name}</h5>
                        <p class="card-text">${course.descriptiom}</p>
                        ${isAdmin ? 
                            <button class="btn btn-warning" onclick="editCourse(${index})">Editar</button>
                            <button class="btn btn-danger" onclick="deleteCourse(${index})">Eliminar</button>
                         : ''}
                    </div>
                </div>
            ;
            container.appendChild(courseCard);
        });
    }

    function showCreateCoursePanel() {
        document.getElementById('create-course').style.display = 'block';
        currentEditIndex = -1;
        document.getElementById('submit-button').style.display = 'block';
        document.getElementById('update-button').style.display = 'none';
        document.getElementById('cancel-button').style.display = 'none';
    }

    function hideCreateCoursePanel() {
        document.getElementById('create-course').style.display = 'none';
        document.getElementById('create-course-form').reset();
    }

    function editCourse(index) {
        if (!isAdmin) {
            alert('No tienes permiso para editar cursos');
            return;
        }
        currentEditIndex = index;
        const course = courses[index];
        document.getElementById('course-name').value = course.name;
        document.getElementById('course-descriptiom').value = course.descriptiom;
        document.getElementById('course-image-url').value = course.image_url;

        document.getElementById('submit-button').style.display = 'none';
        document.getElementById('update-button').style.display = 'block';
        document.getElementById('cancel-button').style.display = 'block';
        showCreateCoursePanel();
    }

    function updateCourse() {
        document.getElementById('create-course-form').dispatchEvent(new Event('submit'));
    }

    function deleteCourse(index) {
        if (!isAdmin) {
            alert('No tienes permiso para eliminar cursos');
            return;
        }
        if (confirm('¿Estás seguro de que deseas eliminar este curso?')) {
            fetch('index.php', {
                method: 'DELETE',
                body: JSON.stringify({ course_id: courses[index].id })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    courses.splice(index, 1);
                    renderCourses();
                }
            })
            .catch(error => console.error('Error al eliminar el curso:', error));
        }
    }

    function cancelEdit() {
        currentEditIndex = -1;
        hideCreateCoursePanel();
    }

    // Cargar los cursos al inicio
    fetchCourses();
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
