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
    protected $id;

    /**
     * @var GameConfig Конфиг
     */
    protected $config;

    /**
     * @var int ИД хода
     */
    protected $turn;

    /**
     * @var Province[] Провинции
     */
    protected $provinces = [ ];

    /**
     * @var Character[] Персонажи игроков
     */
    protected $characters = [ ];

    /**
     * @var Faction[] Фракции
     */
    protected $factions = [ ];

    /**
     * @var Army[] Армии
     */
    protected $armies = [ ];

    /** @var int */
    private $lastCharacterId = 0;
    /** @var int */
    private $lastFactionId = 0;
    /** @var int */
    private $lastArmyId = 0;

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
            "id"              => $this->id,
            "turn"            => $this->turn,
            "provinces"       => $this->provinces,
            "characters"      => $this->characters,
            "factions"        => $this->factions,
            "armies"          => $this->armies,
            "lastCharacterId" => $this->lastCharacterId,
            "lastFactionId"   => $this->lastFactionId,
            "lastArmyId"      => $this->lastArmyId
        ];
    }

    /**
     * Загрузка сырых данных в свойства модели
     */
    protected function processRawData()
    {
        foreach($this->rawData['characters'] as $data){
            $this->characters[] = new Character($data);
        }
        $this->provinces  = [ ];
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
     * Находит персонада по ИД его игрока
     *
     * @param $playerId
     *
     * @return Character|null
     */
    public function getCharacterByPlayerId( $playerId )
    {
        foreach ($this->characters as $character) {
            if ($character->getPlayer()->id == $playerId) {
                return $character;
            }
        }
        return null;
    }

    /**
     * Создает нового персонажа и сохраняет игру
     *
     * @param [] $data
     *
     * @return bool
     */
    public function createCharacter( $data )
    {
        $character = new Character( $data );
        $this->lastCharacterId++;
        $character->setupAsNew($this->lastCharacterId);
        $this->characters[] = $character;

        return $this->save();
    }

    /**
     * Находит существующего персонажа по его ИД в $data и обновляет переданные параметры
     *
     * @param [] $data
     *
     * @return bool
     */
    public function updateCharacter( $data )
    {
        foreach ($this->characters as $character) {
            if ($character->getId() == $data['id']) {
                CVarDumper::dump("HERE");
                $character->setAttributes( $data );
                return $this->save();
            }
        }
        return false;
    }
} 