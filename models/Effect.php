<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class Effect
 *
 * Класс эффекта для классов, трейтов и действий
 */
class Effect extends \JSONModel
{
    /** @var string */
    protected $type;
    /** @var string */
    protected $object;
    /** @var string */
    protected $property;
    /** @var string */
    protected $operation;
    /** @var mixed */
    protected $value;

    /**
     * @param Character $character
     *
     * @return void
     */
    public function apply($character)
    {
        switch ($this->type) {
            // Проверка на значение поля объекта
            case "propertyChange":
                $model = $this->getObject($character);
                // Если объект успешно получен - проверяем значение его свойства на соответствие условию
                if (is_object( $model )) {
                    $propertySetter = "set" . $this->property;
                    $propertyGetter = "get" . $this->property;
                    $propertyValue  = call_user_func( [ $model, $propertyGetter ] );

                    switch($this->operation){
                        case "add":
                            $newValue = $propertyValue + $this->value;
                            break;
                        default :
                            $newValue = $propertyValue;
                            break;
                    }
                    call_user_func( [ $model, $propertySetter ], $newValue );
                }
                break;
            case "flag":
                $model = $this->getObject($character);
                $model->setFlag($this->property, $this->value);
                break;
            default :
                break;
        }
    }

    /**
     * @return null|Character
     */
    private function getObject($character)
    {
        // Получаем объект
        switch ($this->object) {
            case "Character":
                $model = $character;
                break;
            default:
                $model = null;
                break;
        }

        return $model;
    }
}