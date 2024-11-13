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