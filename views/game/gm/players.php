<?php
/**
 * @var $this GameController
 * @var $players Users[] Список игроков, с племенами
 */
?>
<div id="gm_players_list">
    <h2>Игроки</h2>
    <?= CHtml::hiddenField( "player_id" ) ?>
    <?php foreach ($players as $player) { ?>
        <p data-player-id="<?= $player->id ?>">
            <?= $player->person->nickname ?>
            <?php $character = $this->game->getCharacterByPlayerId( $player->id ); ?>
            <?php if ( ! empty( $character )) { ?>
                <span>(<?= $character->getName(); ?>
                    - <?= ( $character->getFaction() ? $character->getFaction()->getName() : "Нет фракции" ) ?>)</span>
                <?php
                $this->widget( 'bootstrap.widgets.TbButton', [
                    'label'       => 'Изменить персонажа',
                    'type'        => 'secondary',
                    'size'        => 'small',
                    "htmlOptions" => [
                        'class' => "edit_character_gm"
                    ]
                ] );
                ?>
            <?php } else { ?>
                <?php
                $this->widget( 'bootstrap.widgets.TbButton', [
                    'label'       => 'Добавить персонажа',
                    'type'        => 'secondary',
                    'size'        => 'small',
                    "htmlOptions" => [
                        'class' => "add_character_gm"
                    ]
                ] );
                ?>
            <?php } ?>
        </p>
    <?php } ?>
</div>
<div id="gm_factions_list">
    <h2>Фракции</h2>
    <?= CHtml::hiddenField( "faction_id" ) ?>
    <?php foreach ($this->game->getFactions() as $faction) { ?>
        <p data-faction-id="<?= $faction->getId() ?>">
            <?= $faction->getName() ?>
            <span>(<?= ( $faction->getLeader() ? $faction->getLeader()->getName() : '<span class="alert-error">Нет лидера</span>' ) ?>
                )</span>
            <?php
            $this->widget( 'bootstrap.widgets.TbButton', [
                'label'       => 'Редактировать фракцию',
                'type'        => 'secondary',
                'size'        => 'small',
                "htmlOptions" => [
                    'class' => "edit_faction_gm"
                ]
            ] );
            ?>
        </p>
    <?php } ?>
    <?php
    $this->widget( 'bootstrap.widgets.TbButton', [
        'label'       => 'Добавить фракцию',
        'type'        => 'secondary',
        'size'        => 'small',
        "htmlOptions" => [
            'class' => "add_faction_gm"
        ]
    ] );
    ?>
</div>