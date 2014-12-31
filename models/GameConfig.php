<?php

/**
 * Class GameConfig
 * Класс для работы с файлами конфигурации
 */
class GameConfig extends JSONModel
{
    /**
     * @var int ИД игры
     */
    private $game_id;

    /**
     * @var string Имя конфига
     */
    private $config_name = 'main';

    /**
     * @var [] Наборы параметров
     */
    private $parameters = [ ];

    /**
     * Конструктор модели
     *
     * @param int $game_id
     */
    public function __construct( $game_id )
    {
        $this->$game_id = $game_id;
    }

    /**
     * Загрузка файла или файлов конфигурации в модель
     *
     * @param string|[] $config
     * @param null|integer $game_id
     *
     * @return GameConfig
     */
    public function load( $config, $game_id = null )
    {
        if ( ! is_array( $config )) {
            $config = [ $config ];
        }
        foreach ($config as $config_name) {
            $this->config_name = $config_name;
            $this->setPaths( $game_id );
            $this->loadFromFile();
        }

        return $this;
    }

    /**
     * @param integer $game_id
     *
     * @return GameConfig
     */
    public function setGameId( $game_id )
    {
        $this->game_id = $game_id;

        return $this;
    }

    /**
     * Установка путей к папке и файлу
     *
     * @param null|integer $game_id
     */
    protected function setPaths( $game_id = null )
    {
        $this->setGameId( $game_id );

        $this->model_file = $this->config_name . ".json";

        $this->model_path = Yii::app()->getModulePath() . "/vestria/data/games/" . $this->game_id . "/config/";
        if ( ! $this->fileExists()) {
            $this->model_path = Yii::app()->getModulePath() . "/vestria/data/common/";
        }
    }

    /**
     * Возвращает запрошенный конфиг в виде массива
     *
     * @param $config_name
     *
     * @return null
     */
    public function getConfigAsArray( $config_name )
    {
        if (isset( $this->parameters[$config_name] )) {
            return $this->parameters[$config_name];
        } else {
            $this->load( $config_name );
            return isset( $this->parameters[$config_name] ) ? $this->parameters[$config_name] : null;
        }
    }

    public function getConfigAsList($config_name)
    {
        $config = $this->getConfigAsArray($config_name);

        $list = [];
        if(isset($config['listed']) && $config['listed'] == 1){
            foreach($config['elements'] as $element){
                $list[$element['id']] = $element['name'];
            }
        }
        return $list;
    }

    /**
     * Загрузка сырых данных в массив параметров
     */
    protected function processRawData()
    {
        $this->parameters[$this->config_name] = $this->raw_data;
    }
} 