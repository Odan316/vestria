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
        foreach ($this->rawData['characters'] as $data) {
            $this->characters[] = new Character( $this, $data );
        }
        $this->provinces = [ ];
        $this->factions  = [ ];
        $this->armies    = [ ];
    }

    /**
     * Создание новой игры
     */
    public function createNewGame()
    {
        $this->save();
    }

    /**
     * Проверяет, соответствует ли состояние игры минимально играбельному
     * 1) у всех фракций есть лидеры
     *
     * @return bool
     */
    public function setupFinished()
    {
        foreach ($this->factions as $faction) {
            if ( ! $faction->getLeaderId()) {
                return false;
            }
        }
        return true;
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
     * @param int $provinceId
     *
     * @return null|Province
     */
    public function getProvince( $provinceId )
    {
        foreach ($this->provinces as $province) {
            if ($province->getId() == $provinceId) {
                return $province;
            }
        }

        return null;
    }

    /**
     * @return Character[]
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * @param int $characterId
     *
     * @return null|Character
     */
    public function getCharacter( $characterId )
    {
        foreach ($this->characters as $character) {
            if ($character->getId() == $characterId) {
                return $character;
            }
        }

        return null;
    }

    /**
     * @return Faction[]
     */
    public function getFactions()
    {
        return $this->factions;
    }

    /**
     * @param int $factionId
     *
     * @return null|Faction
     */
    public function getFaction( $factionId )
    {
        foreach ($this->factions as $faction) {
            if ($faction->getId() == $factionId) {
                return $faction;
            }
        }

        return null;
    }

    /**
     * @return Army[]
     */
    public function getArmies()
    {
        return $this->armies;
    }

    /**
     * @param int $armyId
     *
     * @return Army|null
     */
    public function getArmy( $armyId )
    {
        foreach ($this->armies as $army) {
            if ($army->getId() == $armyId) {
                return $army;
            }
        }

        return null;
    }

    /**
     * Находит персонада по ИД его игрока
     *
     * @param int $playerId
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
        $this->lastCharacterId ++;
        $character->setupAsNew( $this->lastCharacterId );
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
        $character = $this->getCharacter( $data['id'] );
        if ( ! empty( $character )) {
            $character->setAttributes( $data );
            return $this->save();
        }
        return false;
    }
} 