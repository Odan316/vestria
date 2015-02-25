<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class Modifier
 *
 * Класс модификатора для игры, фракций, провинций и персонажей
 */
class Modifier extends \JSONModel
{
    const TYPE_TURN = 1;
    const TYPE_COUNTDOWN = 2;
    const TYPE_PERSIST = 3;

    /** @var string */
    protected $type;
    /** @var string */
    protected $name;
    /** @var string */
    protected $operation;
    /** @var int|float */
    protected $value;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float|int
     */
    public function getValue()
    {
        return $this->value;
    }

    public function jsonSerialize()
    {
        return [
            "type" => $this->type,
            "name" => $this->name,
            "operation" => $this->operation,
            "value" => $this->value
        ];
    }


}