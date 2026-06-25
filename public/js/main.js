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
                showToast('Por favor, completa todos los campos obligatorios.', 'error');
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
 * Muestra una notificación elegante (Toast) en la interfaz.
 * @param {string} msg - El mensaje a mostrar.
 * @param {string} type - El tipo de notificación ('success', 'error', 'info').
 */
function showToast(msg, type = 'success') {
    const container = document.getElementById('notification-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast-notif toast-${type}`;

    // Iconos según el tipo
    let icon = '';
    if (type === 'success') icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
    else if (type === 'error') icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
    else icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>';

    toast.innerHTML = `${icon} <span>${msg}</span>`;
    container.appendChild(toast);

    // Animación de salida y remoción
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 500);
    }, 4000);
}
