<?php
namespace diplomacy\modules\vestria\models;

/**
 * Class LogRow
 *
 * Класс отдельной смтроки в логе хода
 *
 * @method LogRow setText( string $text )
 * @method string getText()
 *
 */
class LogRow extends \JSONModel
{
    /** @var int */
    protected $text;

    /** @var Log */
    protected $log;

    /**
     * @param Log $log
     * @param [] $data
     */
    public function __construct( $log, $data = [ ] )
    {
        $this->log = $log;
        parent::__construct( $data );
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "text" => $this->text
        ];
    }
}