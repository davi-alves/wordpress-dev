<?php get_header(); ?>
<!-- #####
    * O plugin possui as seguintes funções de helper:
        1 - fk_have_slides()   - mesmo funcionamento de 'have_posts()', verifica se existem slides cadastrados no admin
        2 - fk_the_slide()     - mesmo funcionamento de 'the_post()', pega o próximo post da lista
        3 - fk_get_the_slide() -

    * Todas as imagens possuem as 3 propriedades a seguir:

        1- link  - contendo o link cadastrado no admin

        2- big   - contendo os dados da imagem grande, que por sua vez possui as 3 propriedades a seguir:
            2.1 - url    - url completa da imagem grande
            2.2 - width  - largura da imagem grande
            2.3 - height - altura da imagem grande

        3- thumb - contendo os dados da thumbnail, que por sua vez possui as 3 propriedades a seguir:
            3.1 - url    - url completa da thumbnail
            3.2 - width  - largura da thumbnail
            3.3 - height - altura da thumbnail

    * Para customizar esse template crie um arquivo com o mesmo nome (home-featuredslideshow.php) na raiz do tema

     ##### -->

<?php if(fk_have_slides()): ?>

<div class="slideshow-images">
    <?php fk_the_slide(); ?>
    <div class="big">
        <a href="<?php fk_the_link() ?>">
            <img src="<?php fk_the_big_url() ?>">
        </a>
    </div>
    <div class="thumbs">
        <?php while(fk_have_slides()): fk_the_slide(); ?>
            <div class="thumb">
                <a href="<?php fk_the_link(); ?>">
                    <img src="<?php fk_the_thumb_url() ?>" data-ref="<?php fk_the_big_url() ?>">
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php endif; ?>

<?php get_footer(); ?>
