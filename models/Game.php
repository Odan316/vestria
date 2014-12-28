<?php

/**
 * Class Game
 *
 * Модель для работы с игрой
 */
class Game extends JSONModel
{
    /**
     * @var int ИД игры
     */
    private $id;

    /**
     * @var GameConfig Конфиг
     */
    private $config;

    /**
     * @var int ИД хода
     */
    private $turn;

    /**
     * @var Province[] Провинции
     */
    private $provinces = [ ];

    /**
     * @var Character[] Персонажи игроков
     */
    private $characters = [ ];

    /**
     * @var Faction[] Фракции
     */
    private $factions = [ ];

    /**
     * @var Army[] Армии
     */
    private $armies = [ ];

    /**
     * Конструктор модели
     *
     * @param integer $game_id
     * @param integer $turn
     */
    public function __construct( $game_id, $turn = 0 )
    {
        $this->id   = $game_id;
        $this->turn = $turn;
        $this->load();
        $this->config = new GameConfig( $this->id );
    }


    /**
     * Установка путей к папке и файлу
     */
    protected function setPaths()
    {
        if ( ! empty( $this->id )) {
            $this->model_path = Yii::app()->getModulePath() . "/vestria/data/games/" . $this->id . "/turns/" . (integer) $this->turn . "/";
            $this->model_file = "main_save.json";
        }
    }

    /**
     * Загрузка игрового файла в модель
     */
    public function load()
    {
        $this->setPaths();

        $this->loadFromFile();
    }

    /**
     * Сохранение модели в файл
     *
     * @return bool
     */
    public function save()
    {
        $this->setPaths();

        return $this->saveToFile();
    }

    /**
     * Загрузка сырых данных в свойства модели
     */
    protected function processRawData()
    {
        $this->provinces  = [ ];
        $this->characters = [ ];
        $this->factions   = [ ];
        $this->armies     = [ ];
    }

    /**
     * Выгрузка свойств модели в сырые данные
     */
    protected function parseRawData()
    {
        $this->raw_data['id']   = $this->id;
        $this->raw_data['turn'] = $this->turn;
        // TODO: update as possible
        $this->raw_data['provinces']  = [ ];
        $this->raw_data['characters'] = [ ];
        $this->raw_data['factions']   = [ ];
        $this->raw_data['armies']     = [ ];
    }

    /**
     * Создание новой игры
     */
    public function createNewGame()
    {
        $this->save();
    }


    /**
     * @param int $game_id
     *
     * @return Game
     */
    public function setId( $game_id )
    {
        $this->id = $game_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $turn
     *
     * @return Game
     */
    public function setTurn( $turn )
    {
        $this->turn = $turn;

        return $this;
    }

    /**
     * @return int
     */
    public function getTurn()
    {
        return $this->turn;
    }

    /**
     * @return GameConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Province[]
     */
    public function getProvinces()
    {
        return $this->provinces;
    }

    /**
     * @return Character[]
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * @return Faction[]
     */
    public function getFactions()
    {
        return $this->factions;
    }

    /**
     * @return Army[]
     */
    public function getArmies()
    {
        return $this->armies;
    }
} 