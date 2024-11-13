<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
    <title>Inscripciones - CFP N° 61</title>
</head>
<body>
    <div class="bg-info text-white text-center py-4">
        <h1>Inscripciones para Cursos</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="../index.php">Inicio</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="inscripciones.html">Inscripciones</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.html#donde-estamos">¿Dónde estamos?</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.html#trayectos">Trayectos Formativos</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.html#contacto">Contacto</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container my-5">
        <h2>Formulario de Inscripción</h2>
        <form id="registration-form" action="process_registration.php" method="POST">
            <div class="form-group">
                <label for="fullName">Nombre Completo:</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required>
            </div>
            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" class="form-control" id="dni" name="dni" required>
            </div>
            <div class="form-group">
                <label for="birthDate">Fecha de Nacimiento:</label>
                <input type="date" class="form-control" id="birthDate" name="birthDate" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Teléfono:</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="previousStudies">Estudios Previos:</label>
                <input type="text" class="form-control" id="previousStudies" name="previousStudies" required>
            </div>
            <div class="form-group">
                <label for="course">Curso al que desea inscribirse:</label>
                <select class="form-control" id="course" name="course" required>
                    <option value="" disabled selected>Seleccione un curso</option>
                    <!-- Los cursos se cargarán aquí -->
                </select>
            </div>
            <div class="form-group">
                <label for="comments">Comentarios Adicionales:</label>
                <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Inscripción</button>
        </form>
    </div>

    <button id="back-to-top" class="btn btn-info" style="display: none; position: fixed; bottom: 30px; right: 30px; border-radius: 50%;">
        ↑
    </button>

    <div class="bg-info text-white text-center py-4">
        <p>Equipo de Conducción:</p>
        <ul>
            <li>Rector: Joaquín I. Villanueva</li>
            <li>Secretario: Franco A. Alvarez</li>
            <li>Jefe de Taller: Juan Roude</li>
        </ul>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@ popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        window.onload = function() {
            const courses = JSON.parse(localStorage.getItem('courses')) || [];
            const courseSelect = document.getElementById('course');

            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id; // Asumiendo que el id es único
                option.textContent = course.name; // Nombre del curso
                courseSelect.appendChild(option);
            });
        };

        function fetchCourses() {
        fetch('index.php') // Ajusta la ruta si es necesario
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            courses = data; // Asignar los cursos obtenidos a la variable
            renderCourses(); // Renderizar los cursos en la página
        })
        .catch(error => console.error('Error al cargar los cursos:', error));
}
        window.onscroll = function() {
            const button = document.getElementById('back-to-top');
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                button.style.display = "flex"; 
            } else {
                button.style.display = "none";
            }
        };

        document.getElementById('back-to-top').onclick = function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth' 
            });
        };
    </script>
</body>
</html>

<?php
$servername = "localhost"; // Cambia esto si es necesario
$username = "root"; // Cambia esto por tu usuario de base de datos
$password = ""; // Cambia esto por tu contraseña de base de datos
$dbname = "cfp61"; // Nombre de la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = isset($_POST['fullName']) ? $_POST['fullName'] : '';
    $dni = isset($_POST['dni']) ? $_POST['dni'] : '';
    $birthDate = isset($_POST['birthDate']) ? $_POST['birthDate'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $previousStudies = isset($_POST['previousStudies']) ? $_POST['previousStudies'] : '';
    $course = isset($_POST['course']) ? $_POST['course'] : '';
    $comments = isset($_POST['comments']) ? $_POST['comments'] : '';
}

if (empty($fullName) || empty($dni) || empty($birthDate) || empty($email) || empty($phone) || empty($previousStudies) || empty($course)) {
    die("Por favor, complete todos los campos requeridos.");
}

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


// Preparar y vincular
$stmt = $conn->prepare("INSERT INTO inscripciones (fullName, dni, birthDate, email, phone, previousStudies, course, comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $fullName, $dni, $birthDate, $email, $phone, $previousStudies, $course, $comments);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo "Inscripción enviada con éxito.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar conexión
$stmt->close();
$conn->close();
?>