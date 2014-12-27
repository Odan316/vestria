<?php
/**
 * Class GameConfig
 * Класс для работы с файлами конфигурации
 */

class GameConfig extends JSONModel {
    /**
     * @var int ИД игры
     */
    private $game_id;

    /**
     * @var string Имя конфига
     */
    private $config_name = 'main';

    /**
     * @var array Наборы параметров
     */
    private $parameters = array();

    /**
     * Конструктор модели
     *
     * @param null|integer $game_id
     */
    public function __construct($game_id)
    {
        $this->$game_id = $game_id;
    }

    /**
     * Загрузка файла или файлов конфигурации в модель
     *
     * @param string|array $config
     * @param null|integer $game_id
     *
     * @return P13Config
     */
    public function load($config, $game_id = null)
    {
        if(!is_array($config)){
            $config = array($config);
        }
        foreach($config as $config_name){
            $this->config_name = $config_name;
            $this->setPaths($game_id);
            $this->loadFromFile();
        }

        return $this;
    }

    /**
     * Условие для получения конфига конкретной игры
     *
     * @param integer $game_id
     *
     * @return $this
     */
    public function setGameId($game_id)
    {
        $this->game_id = $game_id;

        return $this;
    }

    /**
     * Установка путей к папке и файлу
     *
     * @param string $config_name
     * @param null|integer $game_id
     */
    protected function setPaths($config_name, $game_id = null)
    {
        $this->setGameId($game_id);

        $this->model_file = $this->config_name.".json";

        $this->model_path = Yii::app()->getModulePath()."/project13/data/games/".$this->game_id."/config/";
        if(!$this->fileExists()){
            $this->model_path = Yii::app()->getModulePath()."/project13/data/common/";
        }
    }

    /**
     * Возвращает запрошенный конфиг в виде массива
     *
     * @param $config_name
     * @return null
     */
    public function getConfigAsArray($config_name)
    {
        if(isset($this->parameters[$config_name])){
            return $this->parameters[$config_name];
        } else {
            $this->load($config_name);
            return isset($this->parameters[$config_name]) ? $this->parameters[$config_name] : null;
        }
    }

    /**
     * Загрузка сырых данных в массив параметров
     */
    protected function processRawData()
    {
        $this->parameters[$this->config_name] = $this->raw_data;
    }
} 