<?php
use diplomacy\modules\vestria\controllers\GameController;
use diplomacy\modules\vestria\models\Character;
use diplomacy\modules\vestria\models\PlayerAction;
use diplomacy\modules\vestria\models\Request;
/**
 * @var $this GameController
 * @var $character Character
 * @var $actions PlayerAction[]
 * @var $request Request
 */
?>
<div id="right_panel" class="requests_list">
    <h2>Заявка</h2>
    <?php
    $i = 0;
    foreach($request->getPositions() as $position){
        $i++;
        ?>
        <div class="request_block">
            <?= \CHtml::hiddenField('id', $position->getId(), ["id" => "p".$position->getId()."Id", "class" => "positionId"]);?>
            <label><?= $i ?>:
                <?= \CHtml::dropDownList(
                    "requests",
                    $position->getActionId(),
                    CHtml::listData($actions, "id", "name"),
                    ["id" => "p".$position->getId()."Pos", "class" => "reguest_position"]
                );?>
                <!--<select class="reguest_position" name="requests">
                    <option value="">Выбрать</option>
                    <?php
/*                    foreach($actions as $action){ */?>
                        <option
                            value="<?/*= $action->getId();*/?>"
                            <?/*= $action->getId() == $position->getActionId()? 'selected="selected"': ""*/?>>
                                <?/*= $action->getName(); */?>
                        </option>
                    <?php /*}*/?>
                </select>-->
            </label>
            <div class="request_params">
                <?= $position->getParametersCode() ?>
            </div>
        </div>
    <?php } ?>
    <br/>
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