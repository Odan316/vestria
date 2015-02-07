<?php
namespace diplomacy\modules\vestria\models;

use diplomacy\modules\vestria\components\ModelsFinder;
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
    /** @var string */
    protected $filter;

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
                $objects = (new ModelsFinder($character->getGame()))->getObjects( $this->object, $this->filter, true );
                $code .= \CHtml::dropDownList( $this->name, $value, \CHtml::listData( $objects, "id", "name" ),
                    [ 'class' => 'request_parameter' ] );
                break;
            case "exactValue":
                break;
            default:
                break;
        }

        return $code;
    }
}