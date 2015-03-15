<?php
namespace diplomacy\modules\vestria\models;

/**
 * Class Modifier
 *
 * Класс модификатора для игры, фракций, провинций и персонажей
 *
 * @method Modifier setType( string $type )
 * @method string getType()
 * @method Modifier setName( string $name )
 * @method string getName()
 * @method Modifier setOperation( string $operation )
 * @method string getOperation()
 * @method Modifier setValue( mixed $value )
 * @method int|float getValue()
 *
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

    public function jsonSerialize()
    {
        return [
            "type"      => $this->type,
            "name"      => $this->name,
            "operation" => $this->operation,
            "value"     => $this->value
        ];
    }


}