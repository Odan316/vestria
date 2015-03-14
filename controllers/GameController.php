<?php
namespace diplomacy\modules\vestria\controllers;

use diplomacy\modules\vestria\components\VesController;
use diplomacy\modules\vestria\components\ModelsFinder;
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
        if (\Yii::app()->user->getState( 'game_role' ) == \Game_roles::GM_ROLE) {
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
        if (\Yii::app()->user->getState( 'game_role' ) == \Game_roles::GM_ROLE) {
            /** @var $ClientScript \CClientScript */
            $ClientScript = \Yii::app()->clientScript;
            $ClientScript->registerScriptFile( $this->module->assetsBase . '/js/gm.js' );

            $this->render( 'gm/index', [
                'game' => $this->game,
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
        if (\Yii::app()->user->getState( 'game_role' ) == \Game_roles::PLAYER_ROLE) {
            /** @var $ClientScript \CClientScript */
            $ClientScript = \Yii::app()->clientScript;

            $character = $this->game->getCharacterByPlayerId(\Yii::app()->user->getState( 'uid' ));
            if(!$character){
                $ClientScript->registerScriptFile( $this->module->assetsBase . '/js/player_setup.js' );
                $this->render( 'player_setup', [
                    'classesList' => $this->game->getConfig()->getConfigAsList( "character_classes" ),
                    'provincesList' => $this->game->getConfig()->getConfigAsList( "provinces" )
                ] );
            } else {
                $ClientScript->registerScriptFile( $this->module->assetsBase . '/js/player.js' );
                $this->render( 'player/index', [
                    'character' => $character,
                    'actions' => (new ModelsFinder($this->game))->findActions($character),
                    'request' => $this->game->getRequestByCharacterId($character->getId())
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