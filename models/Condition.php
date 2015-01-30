<?php
namespace diplomacy\modules\vestria\models;
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

    public function test($character)
    {
        $test = false;
        switch ($this->type) {
            // Проверка на значение поля объекта
            case "propertyValue":
                // Получаем объект
                switch ($this->object) {
                    case "Character":
                        $model = $character;
                        break;
                    default:
                        $model = null;
                        break;
                }
                // Если объект успешно получен - проверяем значение его свойства на соответствие условию
                if (is_object( $model )) {
                    $propertyGetter = "get" . $this->property;
                    $propertyValue  = call_user_func( [ $model, $propertyGetter ] );
                    switch($this->is){
                        case "in":
                            $test = in_array( $propertyValue, $this->value );
                            break;
                        default :
                            break;
                    }
                }
                break;
            default :
                break;
        }
        return $test;
    }
}