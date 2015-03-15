<?php
namespace diplomacy\modules\vestria\models;

use diplomacy\modules\vestria\components\ModelsFinder;
/**
 * Class Parameter
 *
 * Класс параметра для действий
 *
 * @method Parameter setType( string $type )
 * @method string getType()
 * @method Parameter setName( string $name )
 * @method string getName()
 * @method Parameter setObject( string $object )
 * @method string getObject()
 * @method string[] getFilters()
 * @method mixed getMax()
 * @method mixed getMin()
 * @method Parameter setLabel( string $label )
 * @method string getLabel()
 * @method Parameter setValue( string $value )
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
    /** @var int|[] */
    protected $max;
    /** @var int|[] */
    protected $min;
    /** @var string */
    protected $label;
    /**
     * Используется для скрытых полей, что бы задать им значение
     * @var mixed
     */
    protected $value;


    /**
     * @param Character $character
     * @param mixed $value
     *
     * @return string
     */
    public function getParameterCode( $character, $value)
    {
        $code = "";
        $htmlOptions = [ 'class' => 'request_parameter'];
        switch ($this->type) {
            case "objectsSelect":
                $objects = (new ModelsFinder($character->getGame()))->getObjects( $character, $this->object, $this->filters, true );
                $code = \CHtml::dropDownList( $this->name, $value, \CHtml::listData( $objects, "id", "name" ),
                    $htmlOptions );
                break;
            case "exactValue":
                if(!empty($this->max)){
                    $htmlOptions["max"] = $this->getValue($character, $this->max);
                }
                if(!empty($this->min)){
                    $htmlOptions["min"] = $this->getValue($character, $this->min);
                }
                $code = \CHtml::textField($this->name, $value, $htmlOptions);
                break;
            case "hiddenValue":
                $value = $this->getValue($character);
                $code = \CHtml::hiddenField($this->name, $value, $htmlOptions);
                break;
            case "colorSelect":
                $code = \Yii::app()->getController()->widget('ext.yii-colorpicker.ColorPicker', [
                    'name' => $this->name,
                    'value' => $value,
                    'htmlOptions' => $htmlOptions
                ], 1);
                break;
            default:
                break;
        }
        if($this->label != "")
            $code = "<label><span>".$this->label."</span>".$code."</label><br/>";

        return $code;
    }

    /**
     * @param Character|null $character
     * @param string $value
     *
     * @return mixed
     */
    protected function getValue($character = null, $value = null)
    {
        if(empty($value)) $value = $this->value;
        if(strpos($value, ".") && $character != null){
                $alias = explode(".", $value);
                $property = array_pop($alias);
                $model = (new ModelsFinder($character->getGame()))->getObject( $character, $alias[0]);
                $value = call_user_func( [ $model, "get".$property ] );
                return $value;
        } else
            return $value;
    }
}