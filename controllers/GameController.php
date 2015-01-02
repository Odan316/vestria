<?php

/**
 * Class GameController
 *
 * Контроллер для работы в Кабинете (Ведущего или Игрока)
 */
class GameController extends Controller
{
    /**
     * Модель пользователя (из базового движка)
     * @var Users $user_model
     */
    protected $userModel;

    /**
     * Модель игры (из базового движка)
     * @var Games $game_model
     */
    protected $gameModel;

    /**
     * Объект игры
     * @var Game
     */
    protected $game;

    /**
     * @var string Название модуля для отображения в тайтле страницы
     */
    public $gameTitle = "Вестрия: Время Перемен";

    /**
     * Перед загрузкой контроллера необходимо
     * - установить общий layout модуля
     * - подключить общие стили и JS
     */
    public function init()
    {
        $this->layout = 'main';
        /** @var $ClientScript CClientScript */
        $ClientScript = Yii::app()->clientScript;
        $ClientScript->registerCssFile( $this->module->assetsBase . '/css/styles.css' );

        parent::init();
    }

    /**
     * Перед загрузкой действия необходимо
     * - проверить наличие или попытаться установить ИД игры в куки
     * - проверить права пользователя на доступ к игре
     * - загрузить базовые модели пользователя и игры
     *
     * @param CAction $action
     *
     * @return bool
     */
    public function beforeAction( $action )
    {
        $gameId = false;
        /** @var $user CWebUser */
        $user = Yii::app()->user;
        if (isset( $this->actionParams['id'] )) {
            $gameId                                = $this->actionParams['id'];
            $cookie                                 = new CHttpCookie( 'gameId', $gameId );
            $cookie->expire                         = time() + 60 * 60 * 24 * 30;
            Yii::app()->request->cookies['gameId'] = $cookie;
        } elseif (isset( Yii::app()->request->cookies['gameId'] )) {
            $gameId = Yii::app()->request->cookies['gameId']->value;
        } elseif ( ! $user->getState( 'gameId' )) {
            $this->redirect( $this->createUrl( 'cabinet/no_such_game' ) );
        }

        if ( ! $user->getState( 'gameRole' )) {
            $userRole = Users2games::model()->findByAttributes( [
                'user_id' => $user->getState( 'uid' ),
                'game_id' => $gameId
            ] );
            if ( ! $userRole) {
                $this->redirect( $this->createUrl( 'cabinet/game_access_denied' ) );
            } else {
                $user->setState( 'game_role', $userRole->role_id );
            }
        }
        $this->userModel = Users::model()->with( 'person' )->findByPk( $user->getState( 'uid' ) );
        $this->gameModel = Games::model()
                                 ->with( 'master_user', 'players_users' )
                                 ->findByPk( $gameId );

        $this->game = new Game( $this->gameModel->id, $this->gameModel->last_turn );

        return parent::beforeAction( $action );
    }

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
            $ClientScript->registerScriptFile($this->module->assetsBase.'/js/gm.js');

            $this->render( 'gm', [
                'players' => $this->gameModel->players_users,
                'classesList' => $this->game->getConfig()->getConfigAsList("character_classes")
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
        $this->render( 'player', [
        ] );
    }

    /**
     * Отображение заглушки, говорящей что страница не доступна этой роли
     */
    public function actionNoAccess()
    {
        $this->render( 'no_access' );
    }

    public function actionGetCharacterDataByPlayerId()
    {
        $playerId = htmlspecialchars($_POST['playerId']);

        $character = $this->game->getCharacterByPlayerId($playerId);

        echo json_encode($character);
    }

    public function actionGetPlayerData()
    {
        $playerId = htmlspecialchars($_POST['playerId']);

        $player = Users::model()->findByPk($playerId);

        echo json_encode($player);
    }

    public function actionGetTraitsByClassId()
    {
        $classId = htmlspecialchars($_POST['classId']);

        $list = [];
        $traitsConfig = $this->game->getConfig()->getConfigAsArray('character_traits');
        foreach($traitsConfig['elements'] as $key => $values){
            if(in_array($classId, $values['classes'])){
                $list[] = ["id" => $values['id'], "name" => $values['name']];
            }
        }

        echo json_encode($list);
    }

    public function actionGetAmbitionsByClassId()
    {
        $classId = htmlspecialchars($_POST['classId']);

        $list = [];
        $ambitionsConfig = $this->game->getConfig()->getConfigAsArray('character_ambitions');
        foreach($ambitionsConfig['elements'] as $key => $values){
            if(in_array($classId, $values['classes'])){
                $list[] = ["id" => $values['id'], "name" => $values['name']];
            }
        }

        echo json_encode($list);
    }
}