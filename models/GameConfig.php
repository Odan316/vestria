<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class GameConfig
 *
 * Класс для работы с файлами конфигурации
 */
class GameConfig extends \JSONModel
{
    /**
     * @var int ИД игры
     */
    private $gameId;

    /**
     * @var string Имя конфига
     */
    private $configName = 'main';

    /**
     * @var [] Наборы параметров
     */
    private $parameters = [ ];

    /**
     * Конструктор модели
     *
     * @param int $gameId
     */
    public function __construct( $gameId )
    {
        $this->gameId = $gameId;
    }

    /**
     * Загрузка файла или файлов конфигурации в модель
     *
     * @param string|[] $config
     * @param null|integer $gameId
     *
     * @return GameConfig
     */
    public function load( $config, $gameId = null )
    {
        if ( ! is_array( $config )) {
            $config = [ $config ];
        }
        foreach ($config as $configName) {
            $this->configName = $configName;
            $this->setPaths( $gameId );
            $this->loadFromFile();
        }

        return $this;
    }

    /**
     * @param integer $gameId
     *
     * @return GameConfig
     */
    public function setGameId( $gameId )
    {
        $this->gameId = $gameId;

        return $this;
    }

    /**
     * Установка путей к папке и файлу
     *
     * @param null|integer $gameId
     */
    protected function setPaths( $gameId = null )
    {
        $this->setGameId( $gameId );

        $this->modelFile = $this->configName . ".json";

        $this->modelPath = \Yii::app()->getModulePath() . "/vestria/data/games/" . $this->gameId . "/config/";
        if ( ! $this->fileExists()) {
            $this->modelPath = \Yii::app()->getModulePath() . "/vestria/data/common/";
        }
    }

    /**
     * Возвращает запрошенный конфиг в виде массива
     *
     * @param $configName
     *
     * @return null
     */
    public function getConfigAsArray( $configName )
    {
        if (isset( $this->parameters[$configName] )) {
            return $this->parameters[$configName];
        } else {
            $this->load( $configName );
            return isset( $this->parameters[$configName] ) ? $this->parameters[$configName] : null;
        }
    }

    /**
     * Возвращает элементы запрошенного конфига в виде массива объектов
     *
     * @param $configName
     *
     * @return array
     */
    public function getConfigAsObjectsArray($configName)
    {
        $config = $this->getConfigAsArray($configName);
        $list = [ ];
        if (isset( $config['listed'] ) && $config['listed'] == 1) {
            foreach ($config['elements'] as $element) {
                $configClass = "\\diplomacy\\modules\\vestria\\models\\".$config["className"];
                $list[] = new $configClass($element);
            }
        }
        return $list;
    }

    /**
     * Возвращает запрошенный конфиг в формате id - name для создания селекта
     *
     * @param $configName
     *
     * @return array
     */
    public function getConfigAsList( $configName )
    {
        $config = $this->getConfigAsArray( $configName );

        $list = [ ];
        if (isset( $config['listed'] ) && $config['listed'] == 1) {
            foreach ($config['elements'] as $element) {
                $list[$element['id']] = $element['name'];
            }
        }
        return $list;
    }

    /**
     * Возвращает элемент запрошенного конфига, содержащего список элементов по его ИД
     *
     * @param $configName
     * @param $elementId
     *
     * @return mixed|null
     */
    public function getConfigElementById( $configName, $elementId )
    {
        $config = $this->getConfigAsArray( $configName );

        if (isset( $config['listed'] ) && $config['listed'] == 1) {
            $configClass = "\\diplomacy\\modules\\vestria\\models\\".$config["className"];
            foreach ($config['elements'] as $element) {
                if ($element['id'] == $elementId) {
                    return new $configClass($element);
                }
            }
        }

        return null;
    }

    /**
     * Загрузка сырых данных в массив параметров
     */
    protected function processRawData()
    {
        $this->parameters[$this->configName] = $this->rawData;
    }
} 