<?php
namespace diplomacy\modules\vestria\models;

/**
 * Class Game
 *
 * Модель для работы с игрой
 */
class Game extends \JSONModel implements \GameInterface
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
    /**
     * @var Request[] Заявки
     */
    protected $requests = [ ];

    /** @var int */
    private $lastCharacterId = 0;
    /** @var int */
    private $lastFactionId = 0;
    /** @var int */
    private $lastArmyId = 0;

    /** @var Map */
    private $map;

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
        $this->map = new Map($this, $this->config->getConfigAsArray("map"));
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
            $this->modelPath = \Yii::app()->getModulePath() . "/vestria/data/games/" . $this->id . "/turns/" . (integer) $this->turn . "/";
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
            "characters"      => $this->characters,
            "factions"        => $this->factions,
            "armies"          => $this->armies,
            "provinces"       => $this->provinces,
            "requests"        => $this->requests,
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
        foreach ($this->rawData['provinces'] as $data) {
            $this->provinces[] = new Province( $this, $data );
        }
        foreach ($this->rawData['characters'] as $data) {
            $this->characters[] = new Character( $this, $data );
        }
        foreach ($this->rawData['factions'] as $data) {
            $this->factions[] = new Faction( $this, $data );
        }
        foreach ($this->rawData['armies'] as $data) {
            $this->armies[] = new Army( $this, $data );
        }
        foreach ($this->rawData['requests'] as $data) {
            $this->requests[] = new Request( $this, $data );
        }

        $this->lastCharacterId = $this->rawData['lastCharacterId'];
        $this->lastFactionId = $this->rawData['lastFactionId'];
        $this->lastArmyId = $this->rawData['lastArmyId'];
    }

    /**
     * Создание новой игры
     */
    public function createNewGame()
    {
        $provincesConfig = $this->getConfig()->getConfigAsArray("provinces");
        foreach($provincesConfig['elements'] as $province){
            $newProv = [
                "id" => $province['id'],
                "name" => $province['name'],
                "nameX" => $province['nameX'],
                "nameY" => $province['nameY'],
                "ownerId" => null,
            ];
            if(isset($province["nameSize"]))
                $newProv["nameSize"] = $province['nameSize'];
            $this->provinces[] = $newProv;
        }
        $this->save();
    }

    /**
     * Проверяет, соответствует ли состояние игры минимально играбельному
     *
     * @return bool
     */
    public function isReady()
    {
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
     * @param [] $criteria
     * @param bool $asArray
     *
     * @return Province[]|[]
     */
    public function getProvinces( $criteria = [], $asArray = false )
    {
        return $this->getModelsList($this->provinces, $criteria, $asArray);
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
     * @param [] $criteria
     * @param bool $asArray
     *
     * @return Character[]|[]
     */
    public function getCharacters( $criteria = [], $asArray = false )
    {
        return $this->getModelsList($this->characters, $criteria, $asArray);
    }

    /**
     * Возвращает список персонажей без фракции
     *
     * @deprecated
     *
     * @param bool $asArray
     *
     * @return Character[]|[]
     */
    public function getCharactersWithoutFaction( $asArray = false )
    {
        //return $this->getModelsList($this->characters, ["factionId" => null], $asArray);

        $list = [ ];
        foreach ($this->characters as $character) {
            if ( ! $character->getFactionId()) {
                $list[] = ( $asArray ? $character->jsonSerialize() : $character );
            }
        }
        return $list;
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
     * @param [] $criteria
     * @param bool $asArray
     *
     * @return Faction[]|[]
     */
    public function getFactions( $criteria = [], $asArray = false )
    {
        return $this->getModelsList($this->factions, $criteria, $asArray);
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
     * @param [] $criteria
     * @param bool $asArray
     *
     * @return Army[]
     */
    public function getArmies( $criteria = [], $asArray = false )
    {
        return $this->getModelsList($this->armies, $criteria, $asArray);
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
     * @param [] $criteria
     * @param bool $asArray
     *
     * @return Request[]|[]
     */
    public function getRequests( $criteria = [], $asArray = false )
    {
        return $this->getModelsList($this->requests, $criteria, $asArray);
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
     * Находит заявку по ИД ее персонажа
     *
     * @param int $characterId
     *
     * @return Request|null
     */
    public function getRequestByCharacterId( $characterId )
    {
        foreach ($this->requests as $request) {
            if ($request->getCharacter()->getId() == $characterId) {
                return $request;
            }
        }
        return null;
    }

    /**
     * Создает нового персонажа и сохраняет игру
     *
     * @param [] $data
     *
     * @return Character
     */
    public function createCharacter( $data )
    {
        $model = new Character( $this, $data );
        $this->lastCharacterId ++;
        $this->characters[] = $model;
        $model->setupAsNew( $this->lastCharacterId );

        $this->save();

        return $model;
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
        $model = $this->getCharacter( $data['id'] );
        if ( ! empty( $model )) {
            $model->setAttributes( $data );
            return $this->save();
        }
        return false;
    }

    /**
     * Создает новую фракцию и сохраняет игру
     *
     * @param [] $data
     *
     * @return Faction
     */
    public function createFaction( $data )
    {
        $data["id"] = $this->lastFactionId;
        $this->lastFactionId ++;

        $model = new Faction( $this, $data );
        $model->setupAsNew();
        $this->factions[] = $model;

        $this->save();

        return $model;
    }

    /**
     * Находит существующую фракцию по ее ИД в $data и обновляет переданные параметры
     *
     * @param [] $data
     *
     * @return bool
     */
    public function updateFaction( $data )
    {
        $model = $this->getFaction( $data['id'] );
        if ( ! empty( $model )) {
            $model->setAttributes( $data );
            return $this->save();
        }
        return false;
    }

    /**
     * Находит существующую фракцию и удаляет ее и чистит связанные с ней объекты
     *
     * @param int $id
     */
    public function destroyFaction( $id )
    {
        foreach($this->factions as $key => $faction) {
            if($faction->getId() == $id)
                unset($this->factions[$key]);
        }
        foreach($this->getCharacters(["factionId" => $id]) as $character) {
            $character->setFactionId(null);
        }
        foreach($this->getProvinces(["ownerId" => $id]) as $province) {
            $province->setOwnerId(null);
        }
    }

    /**
     * Находит существующую провинцию по ее ИД в $data и обновляет переданные параметры
     *
     * @param [] $data
     *
     * @return bool
     */
    public function updateProvince($data)
    {
        $model = $this->getProvince( $data['id'] );
        if ( ! empty( $model )) {
            $model->setAttributes( $data );
            return $this->save();
        }
        return false;
    }

    /**
     * Создает новую заявку и сохраняет игру
     *
     * @param [] $data
     *
     * @return Request
     */
    public function createRequest( $data )
    {
        $model = new Request( $this, $data );
        $this->requests[] = $model;

        $this->save();

        return $model;
    }

    /**
     * Находит существующую заявку по ИД ее персонажа в $data и обновляет переданные параметры
     *
     * @param [] $data
     *
     * @return bool
     */
    public function updateRequest( $data )
    {
        $model = $this->getRequestByCharacterId( $data['characterId'] );
        if ( ! empty( $model )) {
            $model->setAttributes( $data );
            return $this->save();
        }
        return false;
    }

    /**
     * @return Map
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Возвращает список запрошенных объектов
     *
     * @deprecated
     *
     * @param string $modelsName
     * @param [] $criteria
     * @param bool $asArray
     *
     * @return array
     */
    public function getModels( $modelsName, $criteria = [], $asArray = false )
    {
        switch($modelsName){
            case "Army":
                return $this->getArmies($criteria, $asArray);
                break;
            case "Character":
                return $this->getCharacters($criteria, $asArray);
                break;
            case "Faction":
                return $this->getFactions($criteria, $asArray);
                break;
            case "Province":
                return $this->getProvinces($criteria, $asArray);
                break;
            case "Request":
                return $this->getRequests($criteria, $asArray);
                break;
            default:
                return [];
                break;
        }
    }

    /**
     * @param string $objAlias
     * @param string $objectId
     *
     * @return \JSONModel
     */
    public function getObject($objAlias, $objectId)
    {
        if(!empty($objAlias)){
            $objAlias = explode(".", $objAlias);
            $model = call_user_func( [ $this, "get" . array_shift($objAlias) ], $objectId );
            foreach($objAlias as $modelName){
                $model = call_user_func( [ $model, "get" . $modelName ] );
            }

            return $model;
        } else
            return null;
    }

    /**
     * Перемешивает список персонажей
     *
     * @return void
     */
    public function randomizeCharactersOrder()
    {
        shuffle($this->characters);
    }

    /**
     * Очищает список заявок
     */
    public function clearRequests()
    {
        $this->requests = [];
    }
} 