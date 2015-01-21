<?php
namespace diplomacy\modules\vestria\components;

use diplomacy\modules\vestria\models\Game;
use diplomacy\modules\vestria\models\Character;
use diplomacy\modules\vestria\models\PlayerAction;
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
    public function getActions()
    {
        $actions       = $this->game->getConfig()->getConfigAsObjectsArray( "player_actions" );
        $passedActions = [ ];
        /** @var PlayerAction $action */
        foreach ($actions as $action) {
            if ($this->testConditions( $action )) {
                $passedActions[] = $action;
            }
        }
        return $passedActions;
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
    public function getParametersCode($action)
    {
        $code = "";
        foreach($action->getParameters() as $parameter){
            $code .= $this->getParameterCode($parameter);
        }

        return $code;
    }

    /**
     * @param [] $parameter
     *
     * @return string
     */
    public function getParameterCode($parameter)
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
                $code .= \CHtml::dropDownList( $parameter["name"], 0, \CHtml::listData($objects, "id", "name"), ['class' => 'request_parameter'] );
                break;
            default:
                break;
        }

        return $code;
    }
}