<?php
namespace diplomacy\modules\vestria\models;

use diplomacy\modules\vestria\components\ModelsFinder;
use diplomacy\modules\vestria\components\WithFlags;
/**
 * Class Condition
 *
 * Класс условия для абмиций, трейтов и действий
 */
class Condition extends \JSONModel
{
    /** @var string */
    protected $type;
    /** @var string */
    protected $object;
    /** @var string */
    protected $property;
    /** @var string */
    protected $is;
    /** @var mixed */
    protected $value;
    /** @var Condition[] */
    protected $conditions;

    /**
     * @inheritdoc
     */
    public function setAttributes( $data )
    {
        if (isset( $data['conditions'] )) {
            foreach ($data['conditions'] as $conditionData) {
                $this->conditions[] = new Condition( $conditionData );
            }
            unset( $data['conditions'] );
        }
        parent::setAttributes( $data );
    }

    /**
     * @param Character $character
     *
     * @return bool
     */
    public function test($character)
    {
        $test = false;
        switch ($this->type) {
            // Проверка на значение поля объекта
            case "propertyValue":
                $model = (new ModelsFinder($character->getGame()))->getObject( $character, $this->object);
                // Если объект успешно получен - проверяем значение его свойства на соответствие условию
                if (is_object( $model )) {
                    $propertyGetter = "get" . $this->property;
                    $propertyValue  = call_user_func( [ $model, $propertyGetter ] );
                    switch($this->is) {
                        case "in":
                            $test = in_array( $propertyValue, $this->getValue($character) );
                            break;
                        case "notIn":
                            $test = ! in_array( $propertyValue, $this->getValue($character) );
                            break;
                        case "empty":
                            $test = empty( $propertyValue );
                            break;
                        case "notEmpty":
                            $test = ! empty( $propertyValue );
                            break;
                        case "moreThan":
                            $test = $propertyValue > $this->value;
                            break;
                        case "lessThan":
                            $test = $propertyValue < $this->value;
                            break;
                        default :
                            break;
                    }
                }
                break;
            case "hasFlag":
                /** @var WithFlags $model */
                $model = (new ModelsFinder($character->getGame()))->getObject( $character, $this->object);
                $test = $model->hasFlag($this->property);
                break;
            case "or":
                $test = $this->testOr($character);
                break;
            default :
                break;
        }
        return $test;
    }

    /**
     * @param Character|null $character
     *
     * @return array|null
     */
    protected function getValue($character = null)
    {
        if(is_array($this->value))
            return $this->value;
        elseif(strpos($this->value, ".")){
            if($character != null){
                $alias = explode(".", $this->value);
                $property = array_pop($alias);
                $model = (new ModelsFinder($character->getGame()))->getObject( $character, $alias[0]);
                $value = call_user_func( [ $model, "get".$property ] );
                return [$value];
            }
            else
                return [null];
        } else
            return [$this->value];
    }


    /**
     * @param Character $character
     *
     * @return bool
     */
    public function testOr( $character )
    {
        $test = false;
        foreach ($this->conditions as $condition) {
            if ( $condition->test( $character )) {
                $test = true;
                break;
            }
        }
        return $test;
    }
}