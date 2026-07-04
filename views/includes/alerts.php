<?php
// views/includes/alerts.php - Componente para mostrar tarjetones de alerta

function renderAlert($type, $title, $description = "") {
    $allowed_types = ['error', 'warning', 'success', 'info'];
    if (!in_array($type, $allowed_types)) $type = 'info';

    // Títulos por defecto si están vacíos
    if (empty($title)) {
        switch ($type) {
            case 'error': $title = "¡Error!"; break;
            case 'warning': $title = "Advertencia"; break;
            case 'success': $title = "¡Éxito!"; break;
            case 'info': $title = "Información"; break;
        }
    }

    echo '<div class="alert-card alert-' . $type . '" id="alert-card-' . uniqid() . '">';
    echo '  <button class="alert-close" onclick="this.parentElement.style.display=\'none\'">';
    echo '    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
    echo '  </button>';
    echo '  <div class="alert-title">' . htmlspecialchars($title) . '</div>';
    if (!empty($description)) {
        echo '  <div class="alert-description">' . htmlspecialchars($description) . '</div>';
    }
    echo '</div>';

    // Script para auto-dismiss en success/info después de 10s
    if ($type === 'success' || $type === 'info') {
        echo '<script>setTimeout(function(){
            const alerts = document.querySelectorAll(".alert-success, .alert-info");
            alerts.forEach(a => { if(a.style.display !== "none") a.style.opacity = "0"; setTimeout(() => a.style.display = "none", 500); });
        }, 10000);</script>';
    }
}

// Verificar mensajes en la sesión (Flash messages)
if (isset($_SESSION['flash_alert'])) {
    $flash = $_SESSION['flash_alert'];
    renderAlert($flash['type'], $flash['title'], $flash['description']);
    unset($_SESSION['flash_alert']);
}

// Verificar variable $alert local
if (isset($alert)) {
    renderAlert($alert['type'], $alert['title'], $alert['description']);
}
?>
