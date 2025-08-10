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
                alert('Por favor, completa todos los campos obligatorios.');
            }
        });
    });

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
