<?php
use diplomacy\modules\vestria\controllers\GameController;
use diplomacy\modules\vestria\models\Character;
use diplomacy\modules\vestria\models\CharacterAction;
use diplomacy\modules\vestria\models\Request;

/**
 * @var $this GameController
 * @var $character Character
 * @var $actions CharacterAction[]
 * @var $request Request
 */
?>
<div id="right_panel" class="requests_list">
    <h2>Заявка</h2>
    <form id="requests_form">
    <?php
    $i = 0;
    if ( ! empty( $request )) {
        foreach ($request->getPositions() as $position) {
            $i ++;
            ?>
            <?= $this->widget( "diplomacy\\modules\\vestria\\widgets\\RequestPositionWidget",
                [ "actions" => $actions, "position" => $position, "i" => $i ], 1 );
            ?>
        <?php }
    } ?>
    <?= \CHtml::imageButton($this->getModule()->getAssetsBase()."/images/design/add.png", ["class" => "add_request_position"])?>
    </form>
    <div class="clearfix"></div>
    <?php
    $this->widget( 'bootstrap.widgets.TbButton', [
        'label'       => 'Сохранить',
        'type' => 'primary',
        'size'        => 'mediium',
        "htmlOptions" => [
            'class' => "but_request_save"
        ]
    ] );
    ?>
</div>
