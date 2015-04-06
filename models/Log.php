<?php
namespace diplomacy\modules\vestria\models;

/**
 * Логгер хода
 *
 * @method Log setGame( Game $game )
 * @method Game getGame()
 * @method Log setTurn( int $turn )
 * @method int getTurn()
 * @method LogRow[] getRows()
 */

class Log extends \JSONModel
{
    /**
     * @var Game игра
     */
    protected $game;

    /**
     * @var int Номер хода
     */
    protected $turn;

    /** @var LogRow[] */
    protected $rows = [];

    /**
     * Конструктор модели
     *
     * @param Game $game
     * @param integer $turn
     */
    public function __construct( $game, $turn = 0 )
    {
        $this->game   = $game;
        $this->turn = $turn;
        $this->load();
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
        $gameId = $this->game->getId();
        if ( ! empty( $gameId )) {
            $this->modelPath = \Yii::app()->getModulePath() . "/vestria/data/games/" . $gameId . "/turns/" . (integer) $this->turn . "/";
            $this->modelFile = "turn_log.json";
        }
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "gameId" => $this->game->getId(),
            "turn"   => $this->turn,
            "rows"   => $this->rows,
        ];
    }

    /**
     * Загрузка сырых данных в свойства модели
     */
    protected function processRawData()
    {
        foreach ($this->rawData['rows'] as $data) {
            $this->rows[] = new LogRow( $this, $data );
        }
    }

    /**
     * @param string $text
     *
     * @return Log
     */
    public function addRow($text)
    {
        $row = new LogRow($this, [
            "text" => $text
        ]);
        $this->rows[] = $row;

        $this->save();

        return $this;
    }
}