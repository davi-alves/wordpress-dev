# [Flyingkrai] Featured Slider

## COMO USAR O PLUGIN

### helpers

> `fk_have_slides()` - mesmo funcionamento de **have_posts()**, verifica se existem slides cadastrados no admin.

> `fk_the_slide()` - mesmo funcionamento de **the_post()**, pega o próximo slide da lista.

> `fk_get_the_slide()` - mesmo funcionamento de **get_the_post()***, retorna o slide atual da lista como objeto.

> `fk_the_link()` - imprime o link do slide atual.

> `fk_the_big_url()` - imprime a url da imagem grande do slide atual.

> `fk_the_thumb_url()` - imprime a url da thumbnail do slide atual.

> `fk_reset_slides()`- reseta a listagem de slides.

### propriedades das imagens

> `link` - contendo o link cadastrado no admin.

>  `big` - contendo os dados da imagem grande, que por sua vez possui as 3 propriedades a seguir:

>>    * `url` - url completa da imagem grande.
>>    * `width` - largura da imagem grande.
>>    * `height` - altura da imagem grande.


> `thumb` - contendo os dados da thumbnail, que por sua vez possui as 3 propriedades a seguir:

>>    * `url` - url completa da thumbnail.
>>    * `width`  - largura da thumbnail.
>>    * `height` - altura da thumbnail.

### customizando o template
> Para customizar esse template crie um arquivo com o mesmo nome (`home-featuredslideshow.php`) na raiz do tema.
