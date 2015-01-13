<?php
/**
 * @var $this GameController
 */
?>
<div id="gm_map_outer">
    <?= $this->game->getMap()->getSVG(); ?>
</div>
<div id="gm_provinces_list">
    <h2>Фракции</h2>
    <?php foreach ($this->game->getProvinces() as $province) { ?>
        <p data-province-id="<?= $province->getId() ?>">
            <?= $province->getName() ?>
            <span>(<?= ( $province->getOwner() ? $province->getOwner()->getName() : '<span class="alert-error">Нет фракции</span>' ) ?>
                )</span>
            <?php
            $this->widget( 'bootstrap.widgets.TbButton', [
                'label'       => 'Редактировать',
                'type'        => 'secondary',
                'size'        => 'small',
                "htmlOptions" => [
                    'class' => "edit_province_gm"
                ]
            ] );
            ?>
        </p>
    <?php } ?>
</div>

<div id="edit_province_gm" class="modal">
    <?= CHtml::beginForm("/", "POST", ["id" => "Province_form"]); ?>
    <div class="modal_close"></div>
    <h2>Провинция</h2>
    <?= CHtml::hiddenField( "Province[id]" ) ?>
    <label for="Province_name">Название:</label>
    <?= CHtml::textField( "Province[name]" ) ?>
    <br/>
    <label for="Province_ownerId">Владелец:</label>
    <?= CHtml::dropDownList( "Province[ownerId]", 0, CHtml::listData($this->game->getFactions(true), "id", "name") ) ?>
    <?= CHtml::endForm(); ?>
    <br/>
    <?php
    $this->widget( 'bootstrap.widgets.TbButton', [
        'label'       => 'Сохранить',
        'type'        => 'secondary',
        'size'        => 'small',
        "htmlOptions" => [
            'class' => "but_province_save_gm"
        ]
    ] );
    ?>
</div>