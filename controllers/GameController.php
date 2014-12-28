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
    private $user_model;

    /**
     * Модель игры (из базового движка)
     * @var Games $game_model
     */
    private $game_model;

    /**
     * @var string Название модуля для отображения в тайтле страницы
     */
    public $game_title = "Вестрия: Время Перемен";

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
        $game_id = false;
        /** @var $user CWebUser */
        $user = Yii::app()->user;
        if (isset( $this->actionParams['id'] )) {
            $game_id                                = $this->actionParams['id'];
            $cookie                                 = new CHttpCookie( 'game_id', $game_id );
            $cookie->expire                         = time() + 60 * 60 * 24 * 30;
            Yii::app()->request->cookies['game_id'] = $cookie;
        } elseif (isset( Yii::app()->request->cookies['game_id'] )) {
            $game_id = Yii::app()->request->cookies['game_id']->value;
        } elseif ( ! $user->getState( 'game_id' )) {
            $this->redirect( $this->createUrl( 'cabinet/no_such_game' ) );
        }

        if ( ! $user->getState( 'game_role' )) {
            $user_role = Users2games::model()->findByAttributes( [
                'user_id' => $user->getState( 'uid' ),
                'game_id' => $game_id
            ] );
            if ( ! $user_role) {
                $this->redirect( $this->createUrl( 'cabinet/game_access_denied' ) );
            } else {
                $user->setState( 'game_role', $user_role->role_id );
            }
        }
        $this->user_model = Users::model()->with( 'person' )->findByPk( $user->getState( 'uid' ) );
        $this->game_model = Games::model()
                                 ->with( 'master_user', 'players_users' )
                                 ->findByPk( $game_id );

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
            $game = new Game( $this->game_model->id, $this->game_model->last_turn );
            $this->render( 'gm', [
                'game' => $game,
                'players' => $this->game_model->players_users
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
        $game_data = new Game( $this->game_model->id, $this->game_model->last_turn );
        $this->render( 'player', [
            'user_model' => $this->user_model,
            'game_model' => $this->game_model
        ] );
    }

    /**
     * Отображение заглушки, говорящей что страница не доступна этой роли
     */
    public function actionNoAccess()
    {
        $this->render( 'no_access' );
    }
}