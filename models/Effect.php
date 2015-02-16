<?php
namespace diplomacy\modules\vestria\models;

use diplomacy\modules\vestria\components\WithFlags;
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
    protected $objectId;
    /** @var string */
    protected $property;
    /** @var string */
    protected $operation;
    /** @var mixed */
    protected $value;
    /** @var string */
    protected $valueParameter;

    /**
     * TODO: Value
     * @param Game $game
     * @param [] $parameters
     *
     * @return void
     */
    public function apply($game, $parameters)
    {
        $model = $game->getObject($this->object, $this->objectId);
        switch ($this->type) {
            // Проверка на значение поля объекта
            case "propertyChange":
                // Если объект успешно получен - проверяем значение его свойства на соответствие условию
                if (is_object( $model )) {
                    $propertySetter = "set" . $this->property;
                    $propertyGetter = "get" . $this->property;
                    $propertyValue  = call_user_func( [ $model, $propertyGetter ] );

                    switch($this->operation){
                        case "set":
                            $newValue = $propertyValue + $this->value;
                            break;
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
                /** @var WithFlags $model */
                $model->setFlag($this->property, $this->value);
                break;
            default :
                break;
        }
    }

    /**
     * TODO: getValue
     */
    private function getValue()
    {
        if(!empty($this->valueParameter)) {
        }
    }
}