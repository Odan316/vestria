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
     * @param integer $gameId
     * @param integer $turn
     */
    public function __construct( $gameId, $turn = 0 )
    {
        $this->id   = $gameId;
        $this->turn = $turn;
        $this->load();
        $this->config = new GameConfig( $this->id );
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
     * Установка путей к папке и файлу
     */
    protected function setPaths()
    {
        if ( ! empty( $this->id )) {
            $this->modelPath = Yii::app()->getModulePath() . "/vestria/data/games/" . $this->id . "/turns/" . (integer) $this->turn . "/";
            $this->modelFile = "main_save.json";
        }
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "turn" => $this->turn,
            "provinces" => [],
            "characters" => [],
            "factions" => [],
            "armies" => []
        ];
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
     * Создание новой игры
     */
    public function createNewGame()
    {
        $this->save();
    }


    /**
     * @param int $gameId
     *
     * @return Game
     */
    public function setId( $gameId )
    {
        $this->id = $gameId;

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

    /**
     * @param $playerId
     *
     * @return Character|null
     */
    public function getCharacterByPlayerId($playerId)
    {
        foreach($this->characters as $character){
            if($character->getPlayer()->id == $playerId){
                return $character;
            }
        }
        return null;
    }

    /**
     * @param [] $data
     *
     * @return bool
     */
    public function createCharacter($data)
    {
        $this->characters[] = new Character($data);
        return $this->save();
    }

    /**
     * @param [] $data
     *
     * @return bool
     */
    public function updateCharacter($data)
    {
        foreach($this->characters as $character){
            if($character->getId() == $data['id']){
                $this->setAttributes($data);
                return $this->save();
            }
        }
        return false;
    }
} 