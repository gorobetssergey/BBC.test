<?php


?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php if(!empty($items['title'])):?>
            <h3 class="text-center text-uppercase"><?=$items['title']?></h3>
            <h4 class="text-left"><?=$items['text']?></h4>
            <hr>
            <h6>
                <p><b>Дата публикации: </b> <?=$items['date']?></p>
            </h6>
        <?php else:?>
            <h3><?=$items?></h3>
        <?php endif;?>
    </div>
</div>
