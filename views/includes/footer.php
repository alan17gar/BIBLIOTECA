</div> <!-- Cierre del div .container del main-content -->
    </main> <!-- Cierre del main .main-content -->

    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2026 Biblioteca App. Todos los derechos reservados. Un proyecto desarrollado Por Alan Garcia</p>
        </div>
    </footer>

    <!-- Scripts de JavaScript -->
    <!-- Se pueden agregar aquí los scripts globales -->
    <script src="<?php echo BASE_PATH; ?>/public/js/main.js"></script>

    <!-- Scripts específicos de la página (si es necesario) -->
    <?php if (isset($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
            <script src="<?php echo BASE_PATH . '/public/js/' . $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>
