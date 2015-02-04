<?php
use diplomacy\modules\vestria\widgets\PositionParametersWidget;
/**
 * @var $this PositionParametersWidget
 * @var $values []
 */
?>
<?php
foreach($this->action->getParameters() as $parameter){
    $value = isset($values[$parameter->getName()]) ? $values[$parameter->getName()] : null; ?>
    <?= $parameter->getParameterCode($this->character, $value); ?>
<?php } ?>