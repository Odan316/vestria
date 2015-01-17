<?php

/**
 * Class GameController
 *
 * Контроллер для работы в Кабинете (Ведущего или Игрока)
 */
class GameController extends VesController
{
    /**
     * По умолчанию, в зависимости от роли мы грузим
     * - либо страницу ГМа (для ГМа),
     * - либо страницу игрока (для остальных)
     */
    public function actionIndex()
    {
        if (Yii::app()->user->getState( 'game_role' ) == Game_roles::GM_ROLE) {
            $this->actionGM();
        } else {
            $this->actionPlayer();
        }
    }

    /**
     * Страница ГМа (только для ГМа)
     */
    public function actionGM()
    {
        // Сначала проверяем роль
        if (Yii::app()->user->getState( 'game_role' ) == Game_roles::GM_ROLE) {
            /** @var $ClientScript CClientScript */
            $ClientScript = Yii::app()->clientScript;
            $ClientScript->registerScriptFile( $this->module->assetsBase . '/js/gm.js' );

            $this->render( 'gm', [
                'players'     => $this->gameModel->players_users,
                'classesList' => $this->game->getConfig()->getConfigAsList( "character_classes" ),
                'provincesList' => $this->game->getConfig()->getConfigAsList( "provinces" ),
                'mapSVG' => $this->game->getMap()->getSVG()
            ] );
        } else {
            $this->actionNoAccess();
        }
    }

    /**
     * Страница игрока
     */
    public function actionPlayer()
    {
        // Сначала проверяем роль
        if (Yii::app()->user->getState( 'game_role' ) == Game_roles::PLAYER_ROLE) {
            /** @var $ClientScript CClientScript */
            $ClientScript = Yii::app()->clientScript;
            $ClientScript->registerScriptFile( $this->module->assetsBase . '/js/player.js' );

            $character = $this->game->getCharacterByPlayerId(Yii::app()->user->getState( 'uid' ));
            if(!$character){
                $this->render( 'player_setup', [
                    'classesList' => $this->game->getConfig()->getConfigAsList( "character_classes" ),
                    'provincesList' => $this->game->getConfig()->getConfigAsList( "provinces" )
                ] );
            } else {
                $this->render( 'player', [
                    'character' => $character,
                    'actions' => (new PlayerActionFinder($this->game, $character))->getActions()
                ] );
            }
        } else {
            $this->actionNoAccess();
        }
    }

    /**
     * Отображение заглушки, говорящей что страница не доступна этой роли
     */
    public function actionNoAccess()
    {
        $this->render( 'no_access' );
    }
}