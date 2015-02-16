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
    /** @var string[] */
    protected $filters;
    /** @var string */
    protected $label;
    /**
     * Используется для скрытых полей, что бы задать им значение
     * @var mixed
     */
    protected $value;

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
    public function getParameterCode( $character, $value)
    {
        $code = "";
        switch ($this->type) {
            case "objectsSelect":
                $objects = (new ModelsFinder($character->getGame()))->getObjects( $character, $this->object, $this->filters, true );
                $code = \CHtml::dropDownList( $this->name, $value, \CHtml::listData( $objects, "id", "name" ),
                    [ 'class' => 'request_parameter' ] );
                break;
            case "exactValue":
                $code = \CHtml::textField($this->name, $value, [ 'class' => 'request_parameter' ]);
                break;
            case "hiddenValue":
                $value = $this->getValue($character);
                $code = \CHtml::hiddenField($this->name, $value, [ 'class' => 'request_parameter' ]);
                break;
            default:
                break;
        }
        if($this->label != "")
            $code = "<label><span>".$this->label."</span>".$code."</label>";

        return $code;
    }

    /**
     * @param Character|null $character
     *
     * @return mixed
     */
    protected function getValue($character = null)
    {
        if(strpos($this->value, ".") && $character != null){
                $alias = explode(".", $this->value);
                $property = array_pop($alias);
                $model = (new ModelsFinder($character->getGame()))->getObject( $character, $alias[0]);
                $value = call_user_func( [ $model, "get".$property ] );
                return $value;
        } else
            return 0;
    }
}