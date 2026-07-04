// public/js/main.js - Scripts globales de la aplicación

document.addEventListener('DOMContentLoaded', function() {

    // --- Validación de Formularios en Frontend ---
    // Seleccionar todos los formularios que necesiten validación
    const formsToValidate = document.querySelectorAll('#login-form, #book-form, #user-form');

    formsToValidate.forEach(form => {
        form.addEventListener('submit', function(event) {
            let isValid = true;

            // Buscar todos los campos 'required' dentro del formulario
            const requiredFields = form.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                const formGroup = field.closest('.form-group');
                const errorDiv = formGroup.querySelector('.form-error');

                // Resetear errores previos
                if (errorDiv) errorDiv.textContent = '';
                field.style.borderColor = '';

                // Validar si el campo está vacío
                if (field.value.trim() === '') {
                    isValid = false;
                    field.style.borderColor = '#dc3545'; // Color de error
                    if (errorDiv) {
                        errorDiv.textContent = 'Este campo es obligatorio.';
                    } else {
                        // Si no hay un div de error, añadirlo
                        const newError = document.createElement('div');
                        newError.className = 'form-error';
                        newError.style.color = '#dc3545';
                        newError.style.fontSize = '0.875em';
                        newError.style.marginTop = '0.25rem';
                        newError.textContent = 'Este campo es obligatorio.';
                        formGroup.appendChild(newError);
                    }
                }
            });

            // Si el formulario no es válido, prevenir el envío
            if (!isValid) {
                event.preventDefault();
                showToast('Por favor, completa todos los campos obligatorios.', 'warning');
            }
        });
    });

    // --- Menú Móvil ---
    const menuToggle = document.getElementById('menu-toggle');
    const mainNav = document.getElementById('main-nav');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function() {
            mainNav.classList.toggle('active');
        });
    }

    // --- Confirmación antes de eliminar ---
    // Aunque ya hay un `onclick` en el HTML, este es un enfoque más moderno y centralizado.
    // Lo dejamos comentado para no duplicar la funcionalidad, pero es una buena práctica.
    /*
    const deleteButtons = document.querySelectorAll('.btn-danger');
    deleteButtons.forEach(button => {
        // Asegurarse de que no estamos añadiendo el listener a un botón de logout
        if (button.href && button.href.includes('/delete')) {
            button.addEventListener('click', function(event) {
                if (!confirm('¿Estás seguro de que quieres realizar esta acción?')) {
                    event.preventDefault();
                }
            });
        }
    });
    */

    console.log('Biblioteca App JS inicializado.');
});

/**
 * Muestra una notificación elegante (Toast) similar a los tarjetones
 * @param {string} msg - El mensaje a mostrar
 * @param {string} type - El tipo (success, error, warning, info)
 */
function showToast(msg, type = 'info') {
    const container = document.getElementById('notification-container') || createNotificationContainer();

    const toast = document.createElement('div');
    toast.className = `alert-card alert-\${type} toast-notif`;
    toast.style.marginBottom = '10px';
    toast.style.width = '100%';
    toast.style.maxWidth = '400px';
    toast.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';

    toast.innerHTML = `
        <button class="alert-close" onclick="this.parentElement.remove()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        <div class="alert-title">\${type.toUpperCase()}</div>
        <div class="alert-description">\${msg}</div>
    `;

    container.appendChild(toast);

    // Auto eliminar
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(20px)';
        setTimeout(() => toast.remove(), 500);
    }, 5000);
}

function createNotificationContainer() {
    const container = document.createElement('div');
    container.id = 'notification-container';
    container.style.position = 'fixed';
    container.style.top = '20px';
    container.style.right = '20px';
    container.style.zIndex = '9999';
    container.style.display = 'flex';
    container.style.flexDirection = 'column';
    container.style.alignItems = 'flex-end';
    document.body.appendChild(container);
    return container;
}
