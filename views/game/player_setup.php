<?php
use vestria\controllers\GameController;
/**
 * @var $this GameController
 * @var $classesList [] Список доступных классов для персонажей
 * @var $provincesList [] Список доступных провинций
 */
$this->setPageTitle( $this->getModule()->getTitle() . ' - Создание нового персонажа' );
?>
<div id="new_character">
    <?= CHtml::beginForm( "/", "POST", [ "id" => "Character_form" ] ); ?>
    <?= CHtml::hiddenField( "Character[playerId]", Yii::app()->user->getState( 'uid' ) ) ?>
    <h2>Создание нового персонажа</h2>
    <label for="Character_name">Имя:</label>
    <?= CHtml::textField( "Character[name]" ) ?>
    <br/>
    <label for="Character_classId">Класс:</label>
    <?= CHtml::dropDownList( "Character[classId]", 0, $classesList ) ?>
    <br/>
    <label for="Character_traitId">Черта:</label>
    <?= CHtml::dropDownList( "Character[traitId]", 0, [ ] ) ?>
    <br/>
    <label for="Character_ambitionId">Амбиция:</label>
    <?= CHtml::dropDownList( "Character[ambitionId]", 0, [ ] ) ?>
    <br/>
    <label for="Character_provinceId">Стартовая провинция:</label>
    <?= CHtml::dropDownList( "Character[provinceId]", 0, $provincesList ) ?>
    <?= CHtml::endForm(); ?>
    <br/>
    <?php
    $this->widget( 'bootstrap.widgets.TbButton', [
        'label'       => 'Сохранить',
        'type'        => 'secondary',
        'size'        => 'small',
        "htmlOptions" => [
            'class' => "but_character_save_new"
        ]
    ] );
    ?>
</div>