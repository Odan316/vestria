<?php
namespace diplomacy\modules\vestria\components;

use diplomacy\modules\vestria\models\Game;
use diplomacy\modules\vestria\models\Character;
use diplomacy\modules\vestria\models\PlayerAction;
use diplomacy\modules\vestria\models\RequestPosition;
/**
 * Class PlayerActionFinder
 *
 * Класс для получения действий игрока, подходящих ему
 */
class PlayerActionHandler
{
    /** @var Game */
    private $game;
    /** @var Character */
    private $character;

    /**
     * @param Game $game
     * @param Character $character
     */
    public function __construct( $game, $character )
    {
        $this->game      = $game;
        $this->character = $character;
    }

    /**
     * @return PlayerAction[]
     */
    public function getActions($as_array = false)
    {
        $actions       = $this->game->getConfig()->getConfigAsObjectsArray( "player_actions" );
        $passedActions = [ ];
        /** @var PlayerAction $action */
        foreach ($actions as $action) {
            if ($this->testConditions( $action )) {
                $passedActions[] = $action;
            }
        }
        if(!$as_array){
            return $passedActions;
        } else {
            return $this->game->makeList($passedActions);
        }
    }

    /**
     * @param PlayerAction $action
     *
     * @return bool
     */
    private function testConditions( $action )
    {
        $test = true;
        foreach ($action->getConditions() as $condition) {
            if ( ! $this->testCondition( $condition )) {
                $test = false;
                break;
            }
        }
        return $test;
    }

    /**
     * @param [] $condition
     *
     * @return bool
     */
    private function testCondition( $condition )
    {
        $test = true;
        switch ($condition["type"]) {
            // Проверка на значение поля объекта
            case "propertyValue":
                // Получаем объект
                switch ($condition["object"]) {
                    case "Character":
                        $object = $this->character;
                        break;
                    default:
                        $object = null;
                        break;
                }
                // Если объект успешно получен - проверяем значение его свойства на соответствие условию
                if (is_object( $object )) {
                    $propertyGetter = "get" . $condition["property"];
                    $propertyValue  = call_user_func( [ $object, $propertyGetter ] );
                    if ( ! ( isset( $condition["in"] ) && in_array( $propertyValue, $condition["in"] ) )) {
                        $test = false;
                    }
                } else {
                    $test = false;
                }
                break;
            default :
                $test = false;
                break;
        }
        return $test;
    }

    /**
     * @param PlayerAction $action
     *
     * @return string
     */
    public function getParametersCode($action, $values = [])
    {
        $code = "";
        foreach($action->getParameters() as $parameter){
            $value = isset($values[$parameter['name']]) ? $values[$parameter['name']] : null;
            $code .= $this->getParameterCode($parameter, $value);
        }

        return $code;
    }

    /**
     * @param [] $parameter
     *
     * @return string
     */
    public function getParameterCode($parameter, $value = 0)
    {
        $code = "";
        switch($parameter["type"]){
            case "objectsSelect":
                switch($parameter["objectsType"]){
                    case "save":
                        $objects = $this->game->getModels($parameter["objectsClass"], true);
                        break;
                    default:
                        $objects = [];
                        break;
                }
                $code .= \CHtml::dropDownList( $parameter["name"], $value, \CHtml::listData($objects, "id", "name"), ['class' => 'request_parameter'] );
                break;
            default:
                break;
        }

        return $code;
    }

    /**
     * @param RequestPosition $position
     */
    public function applicatePosition($position)
    {
        $parameters = $position->getParameters();
        $effects = $position->getAction()->getEffects();
        foreach($effects as $effect) {
            switch ($effect['type']){
                case "propertyChange":
                    // Получаем объект
                    switch ($effect["object"]) {
                        case "Character":
                            $object = $this->character;
                            break;
                        default:
                            $object = null;
                            break;
                    }
                    // Если объект успешно получен - проверяем значение его свойства на соответствие условию
                    if (is_object( $object )) {
                        $propertySetter = "set" . $effect["property"];
                        $valueType = array_keys($effect['value'])[0];
                        switch($valueType){
                            case "parameter":
                                $parameterName = $effect['value']['parameter'];
                                $value = $parameters[$parameterName];
                                break;
                            default:
                                $propertyGetter = "get" . $effect["property"];
                                $value = call_user_func( [ $object, $propertyGetter ] );
                                break;
                        }
                        call_user_func( [ $object, $propertySetter ], [$value] );
                    }
                    break;
                default:
                    break;
            }
        }
    }
}