<?php
/**
 * @var $this GameController
 */
$this->setPageTitle($this->gameTitle.' - Панель игрока');
?>

<div class="left_panel">

</div>
<div class="central_panel">
    <div id="players_map">
        <!--<object type="image/svg+xml" data="<?/*=$this->module->assetsBase*/?>/images/misc/vestria_polit.svg">Your browser does not support SVG</object>-->
        <?= $mapSVG;?>
    </div>
    <div id="players_map_legend">

    </div>
</div>
<div class="right_panel">

</div>
<div class="clearfix"></div>