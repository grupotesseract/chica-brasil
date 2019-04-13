<footer class="footer ciloe-footer-builder footer-id-<?php echo esc_attr( get_the_ID() ); ?>">
    <div class="footer-wrapper">
        <div class="col-md-6">
            <?php dynamic_sidebar("footer_left_side"); ?>
        </div>

        <div class="col-md-6">
            <?php dynamic_sidebar("footer_right_side"); ?>
        </div>
    </div>

    <div class="copyright-wrapper col-md-12">
        <div class="chica-infos col-md-6">
            <h1>Chica Brasil Confecções LTDA,</h1>
            <p>07.804.056/0001-48,</p>
            <p>Rua Doutor Antonio Prudente, Nº 03-90, salas 01 e 03,</p>
            <p>Jardim Estoril II, CEP 17016-010, Bauru-SP</p>
        </div>
        <div class="development-infos col-md-6">
            <p>Desenvolvido por:</p>

            <a target="_blank" href="https://www.grupotesseract.com.br/">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo-tesseract.png" alt="Logo Grupo Tesseract">
            </a>

            <a target="_blank" href="https://coletivoboitata.com.br/">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo-boitata.png" alt="Logo Coletivo Boitatá">
            </a>
        </div>
    </div>
</footer>
