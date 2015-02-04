<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class Parameter
 *
 * Класс параметра для действий
 */
class Parameter extends \JSONModel
{
    /** @var string */
    protected $type;
    /** @var string */
    protected $name;
    /** @var string */
    protected $object;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Character $character
     * @param mixed $value
     *
     * @return string
     */
    public function getParameterCode( $character, $value )
    {
        $code = "";
        switch ($this->type) {
            case "objectsSelect":
                $objects = $character->getGame()->getObjects( $this->object, true );
                $code .= \CHtml::dropDownList( $this->name, $value, \CHtml::listData( $objects, "id", "name" ),
                    [ 'class' => 'request_parameter' ] );
                break;
            default:
                break;
        }

        return $code;
    }
}