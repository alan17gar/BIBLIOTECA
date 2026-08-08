# Módulo de Seguimiento de Órdenes de Producción (Odoo 18.0)

Este módulo para Odoo 18.0 permite a las empresas del sector de producción registrar y seguir el progreso de las órdenes de producción, gestionar la producción diaria, asignarla a lotes y generar reportes semanales.

## Funcionalidades Principales

- **Gestión de Órdenes de Producción**: Crea órdenes con un rubro, cantidad comprometida en kg y fecha límite.
- **Seguimiento de Producción Diaria**: Registra la producción de cada día, vinculada a una orden y a un lote específico.
- **Cálculos Automatizados**: El sistema calcula en tiempo real la cantidad producida y lo que falta por cumplir.
- **Gestión de Lotes**: Agrupa la producción en lotes para un mejor seguimiento de la trazabilidad.
- **Validación de Sobreproducción**: Impide que se registre más producción de la comprometida en la orden.
- **Alertas y Estados**: Las órdenes cambian de estado automáticamente (`Borrador`, `En Progreso`, `Hecho`) y alertan si están vencidas.
- **Reportes Semanales**: Genera reportes en PDF y XLSX con el resumen de producción de la semana.
- **Seguridad por Roles**: Define permisos para usuarios (`production_user`) y gerentes (`production_manager`).

---

## Cómo Instalar y Correr el Módulo

Sigue estos pasos para instalar el módulo en tu entorno de Odoo.

### Prerrequisitos

1.  **Odoo 18.0 instalado**: Asegúrate de tener una instancia de Odoo 18.0 funcionando.
2.  **Dependencia `report_xlsx`**: Este módulo es necesario para generar reportes en formato Excel.
    - Descárgalo desde la Odoo Community Association (OCA). Puedes encontrarlo en su [repositorio de GitHub](https://github.com/OCA/reporting-engine).
    - Asegúrate de descargar la versión compatible con Odoo 18.0.
    - Coloca la carpeta `report_xlsx` dentro de tu directorio de `addons`.

### Pasos de Instalación

1.  **Descargar el Módulo**:
    - Obtén la carpeta `production_order_tracking` con todo el código fuente.

2.  **Copiar el Módulo a los Addons**:
    - Navega al directorio donde tienes instalado Odoo.
    - Encuentra la carpeta `addons`.
    - Copia la carpeta completa `production_order_tracking` dentro de la carpeta `addons`.

3.  **Reiniciar el Servidor de Odoo**:
    - Para que Odoo detecte el nuevo módulo (y sus dependencias), es fundamental reiniciar el servicio.
    - Si corres Odoo desde la línea de comandos, puedes detenerlo con `Ctrl+C` y volver a iniciarlo. Si es un servicio de sistema (Linux/Windows), usa el gestor de servicios correspondiente.

4.  **Activar el Modo de Desarrollador**:
    - Abre Odoo en tu navegador.
    - Ve a `Ajustes`.
    - En la parte inferior, haz clic en `Activar el modo de desarrollador`.

5.  **Actualizar la Lista de Aplicaciones**:
    - Una vez en modo desarrollador, ve al menú `Aplicaciones`.
    - Haz clic en `Actualizar la lista de aplicaciones` en el submenú.
    - Confirma la actualización en la ventana emergente.

6.  **Instalar el Módulo**:
    - En la barra de búsqueda del menú `Aplicaciones`, elimina el filtro "Aplicaciones" por defecto (haciendo clic en la 'x' de la etiqueta).
    - Busca `Production Order Tracking`.
    - Verás el módulo listo para ser instalado. Haz clic en el botón `Instalar`.

¡Listo! Una vez instalado, verás un nuevo menú principal llamado `Producción` en tu dashboard de Odoo, desde donde podrás acceder a todas las funcionalidades del módulo.