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
    /** @var string */
    protected $valueExact;

    /**
     * Используется при применении эффекта, что бы не передавать параметры во все функции внутри одного класса
     * @var []
     */
    private $parameters = [];

    /**
     * TODO: Value
     * @param Game $game
     * @param [] $parameters
     *
     * @return void
     */
    public function apply($game, $parameters)
    {
        $this->parameters = $parameters;
        $model = $game->getObject($this->object, $this->getParameterValue($this->objectId));
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
                            $newValue = $this->getValue($parameters);
                            break;
                        case "add":
                            $newValue = $propertyValue + $this->getValue($parameters);
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
                $model->setFlag($this->property, $this->getValue($parameters));
                break;
            default :
                break;
        }
    }

    /**
     * @return mixed
     */
    private function getValue()
    {
        if (!empty($this->valueExact)){
            return $this->valueExact;
        } elseif(!empty($this->valueParameter)){
            return $this->getParameterValue($this->valueParameter);
        } else  {
            return null;
        }
    }

    protected function getParameterValue($parameterName)
    {
        if(isset($this->parameters[$parameterName]))
            return $this->parameters[$parameterName];
        else
            return 0;
    }
}