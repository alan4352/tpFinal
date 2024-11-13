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
    event.preventDefault();
    if (!isAdmin) {
        alert('No tienes permiso para crear cursos');
        return;
    }
    const courseName = document.getElementById('course-name').value;
    const courseDescription = document.getElementById('course-description').value;
    const courseImageUrl = document.getElementById('course-image-url').value;

    const formData = new FormData();
    formData.append('name', courseName);
    formData.append('description', courseDescription);
    formData.append('image_url', courseImageUrl);

    if (currentEditIndex !== -1) {
        formData.append('update', true);
        formData.append('course_id', courses[currentEditIndex].id);
    }

    fetch('api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            fetchCourses();
            document.getElementById('create-course-form').reset();
            hideCreateCoursePanel();
        }
    })
    .catch(error => console.error('Error al enviar el formulario:', error));
});

function fetchCourses() {
    fetch('api.php')
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
        courseCard.innerHTML = `
            <div class="card mb-4">
                <img src="${course.image_url}" class="card-img-top" alt="${course.name}">
                <div class="card-body">
                    <h5 class="card-title">${course.name}</h5>
                    <p class="card-text">${course.description}</p>
                    ${isAdmin ? `
                        <button class="btn btn-warning" onclick="editCourse(${index})">Editar</button>
                        <button class="btn btn-danger" onclick="deleteCourse(${index})">Eliminar</button>
                    ` : ''}
                </div>
            </div>
        `;
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
}

function editCourse(index) {
    if (!isAdmin) {
        alert('No tienes permiso para editar cursos');
        return;
    }
    currentEditIndex = index;
    const course = courses[index];
    document.getElementById('course-name').value = course.name;
    document.getElementById('course-description').value = course.description;
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
        fetch('api.php', {
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