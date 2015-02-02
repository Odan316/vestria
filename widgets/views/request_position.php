<?php
use diplomacy\modules\vestria\widgets\RequestPositionWidget;
use diplomacy\modules\vestria\models\CharacterAction;
use diplomacy\modules\vestria\models\RequestPosition;
/**
 * @var $this RequestPositionWidget
 * @var $actions CharacterAction[]
 * @var $position RequestPosition|null
 * @var $i int
 */
?>
<?php
$positionId = !empty($this->position) ? $this->position->getId() : 0;
$actionsListData = \CMap::mergeArray([0 => "Выберите"], \CHtml::listData(\JSONModel::makeList($this->actions), "id", "name"));
?>
<div class="request_block">
    <?= \CHtml::imageButton($this->getController()->getModule()->getAssetsBase()."/images/design/close.png", ["class" => "delete_request_position"])?>
    <?= \CHtml::hiddenField('id', $positionId, ["id" => "p".$positionId."Id", "class" => "positionId"]);?>
    <label><span class="position_num"><?= $this->i ?></span>:
        <?= \CHtml::dropDownList(
            "requests",
            (!empty($this->position) ? $this->position->getActionId() : 0),
            $actionsListData,
            ["id" => "p".$positionId."Pos", "class" => "reguest_position"]
        );?>
    </label>
    <div class="request_params">
    <?php if(!empty($this->position)){?>
        <?= $this->position->getParametersCode() ?>
    <?php } ?>
    </div>
</div>