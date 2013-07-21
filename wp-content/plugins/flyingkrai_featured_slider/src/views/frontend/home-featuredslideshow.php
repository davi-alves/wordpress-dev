<?php get_header(); ?>

<!-- #####
    * Todas as imagens possuem as 3 propriedades a seguir:

        1- ['link']  - contendo o link cadastrado no admin

        2- ['big']   - contendo os dados da imagem grande, que por sua vez possui as 3 propriedades a seguir:
            2.1 - ['url']    - url completa da imagem grande
            2.2 - ['width']  - largura da imagem grande
            2.3 - ['height'] - altura da imagem grande

        3- ['thumb'] - contendo os dados da thumbnail, que por sua vez possui as 3 propriedades a seguir:
            3.1 - ['url']    - url completa da thumbnail
            3.2 - ['width']  - largura da thumbnail
            3.3 - ['height'] - altura da thumbnail

    * Para customizar esse template crie um arquivo com o mesmo nome (home-featuredslideshow.php) na raiz do tema

     ##### -->

<!-- GET IMAGES -->
<!-- Utilize essa função para pegar as imagens cadastradas no admin passando como parâmetro quantas imagens devem vir -->
<?php $images = flyingkrai_get_slideshow_images(3) ?>
<!-- /GET IMAGES -->
<!-- GET FIRST IMAGE -->
<!-- Utilize essa função para remover apenas o primeiro elemento do array das imagens e exibir-lo separadamente -->
<?php $first = array_shift($images) ?>
<!-- /GET FIRST IMAGE -->

<div class="slideshow-images">
    <div class="big">
        <a href="<?php print $first['link'] ?>">
            <img src="<?php print $first['big']['url'] ?>">
        </a>
    </div>

    <div class="thumbs">
        <?php foreach ($images as $image): ?>
            <div class="thumb">
                <a href="<?php print $image['link'] ?>">
                    <img src="<?php print $image['thumb']['url'] ?>">
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php get_footer(); ?>
