<?php
namespace diplomacy\modules\vestria\models;

use diplomacy\modules\vestria\components\WithModifiers;
use diplomacy\modules\vestria\components\WithFlags;
/**
 * Class Effect
 *
 * Класс эффекта для классов, трейтов и действий
 *
 * @method Effect setType( string $type )
 * @method string getType()
 * @method Effect setObject( string $object )
 * @method string getObject()
 * @method Effect setObjectId( string $objectId )
 * @method string getObjectId()
 * @method Effect setProperty( string $property )
 * @method string getProperty()
 * @method Effect setOperation( string $operation )
 * @method string getOperation()
 * @method Effect setValueParameter( string $valueParameter )
 * @method string getValueParameter()
 * @method Effect setValueExact( string $valueExact )
 * @method string getValueExact()
 * @method Effect setValueCalculate( array $valueCalculate )
 * @method [] getValueCalculate()
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
    /** @var string */
    protected $valueParameter;
    /** @var string */
    protected $valueExact;
    /** @var [] */
    protected $valueCalculate;

    /**
     * Используется при применении эффекта, что бы не передавать параметры во все функции внутри одного класса
     * @var []
     */
    private $parameters = [];
    /**
     * @var Game
     */
    private $game;

    /**
     * @param Game $game
     * @param [] $parameters
     *
     * @return void
     */
    public function apply($game, $parameters)
    {
        $this->game = $game;
        $this->parameters = $parameters;
        $model = $this->game->getObject($this->object, $this->getParameterValue($this->objectId));
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
                            $newValue = $this->getValue();
                            break;
                        case "add":
                            $newValue = $propertyValue + $this->getValue();
                            break;
                        case "subtract":
                            $newValue = $propertyValue - $this->getValue();
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
                $model->setFlag($this->property, $this->getValue());
                break;
            case "turnModifier":
            case "persistModifier":
                switch ($this->type){
                    case "turnModifier":
                        $type = Modifier::TYPE_TURN;
                        break;
                    case "countdownModifier":
                        $type = Modifier::TYPE_COUNTDOWN;
                        break;
                    case "persistModifier":
                        $type = Modifier::TYPE_PERSIST;
                        break;
                    default:
                        $type = 0;
                        break;
                }
                $data = [
                    "type" => $type,
                    "name" => $this->property,
                    "operation" => $this->operation,
                    "value" => $this->getValue()
                ];
                /** @var WithModifiers $model */
                $modifier = new Modifier($data);
                $model->setModifier($modifier);
                break;
            case "createObject":
                switch($this->object){
                    case "Faction":
                        $this->createFaction();
                        break;
                    case "Army":
                        $this->createArmy();
                        break;
                }
                break;
            case "destroyObject":
                switch($this->object){
                    case "Faction":
                        $this->destroyFaction();
                        break;
                    case "Army":
                        $this->destroyArmy();
                        break;
                }
                break;
            case "factionRequest":
                $this->makeFactionRequest();
                break;
            default :
                break;
        }
    }

    /**
     * @return int
     */
    private function getValue()
    {
        if(!empty($this->valueCalculate)){
            return $this->getValueByType("valueCalculate", $this->valueCalculate);
        } elseif (!empty($this->valueExact)){
            return $this->getValueByType("valueExact", $this->valueExact);
        } elseif(!empty($this->valueParameter)){
            return $this->getValueByType("valueParameter", $this->valueParameter);
        } else  {
            return 0;
        }
    }

    /**
     * @param $type
     * @param $valueExpr
     *
     * @return int
     */
    private function getValueByType($type, $valueExpr)
    {
        switch($type){
            case "valueExact":
                return $valueExpr;
                break;
            case "valueParameter":
                return $this->getParameterValue($this->valueParameter);
                break;
            case "valueCalculate":
                return $this->calculateValue($this->valueCalculate);
                break;
            case "valueProperty":
                $model = $this->game->getObject($valueExpr["object"], $this->getParameterValue($valueExpr["objectId"]));
                return call_user_func( [ $model, "get" . $valueExpr["property"] ] );
                break;
            default:
                return (int)$valueExpr;
                break;
        }
    }

    /**
     * @param string $parameterName
     *
     * @return int
     */
    protected function getParameterValue($parameterName)
    {
        if(isset($this->parameters[$parameterName]))
            return $this->parameters[$parameterName];
        else
            return 0;
    }

    /**
     * @param [] $calculateExpr
     *
     * @return int
     */
    protected function calculateValue($calculateExpr = [])
    {
        $result = 0;
        switch(array_keys($calculateExpr)[0]){
            case "sum":
                foreach($calculateExpr["sum"] as $paramName => $paramExpr){
                    $result += $this->getValueByType($paramName, $paramExpr);
                }
                break;
            case "subtract":
                $arg1 = $this->getValueByType(array_keys($calculateExpr["subtract"])[0], $calculateExpr["subtract"][array_keys($calculateExpr["subtract"])[0]]);
                $arg2 = $this->getValueByType(array_keys($calculateExpr["subtract"])[1], $calculateExpr["subtract"][array_keys($calculateExpr["subtract"])[1]]);
                $result = $arg1 - $arg2;
                break;
            case "multiply":
                $result = 1;
                foreach($calculateExpr["multiply"] as $paramName => $paramExpr){
                    $result = round($result * $this->getValueByType($paramName, $paramExpr));
                }
                break;
            case "divide":
                $arg1 = $this->getValueByType(array_keys($calculateExpr["divide"])[0], $calculateExpr["divide"][array_keys($calculateExpr["divide"])[0]]);
                $arg2 = $this->getValueByType(array_keys($calculateExpr["divide"])[1], $calculateExpr["divide"][array_keys($calculateExpr["divide"])[1]]);
                $result = round($arg1 / $arg2);
                break;
            default:
                break;
        }
        return $result;
    }

    /**
     * Creates new Faction
     */
    private function createFaction()
    {
        $this->game->createFaction(
            [
                "name" => $this->getParameterValue("name"),
                "leaderId" => $this->getParameterValue("leaderId"),
                "color" => $this->getParameterValue("color")
            ]
        );
    }

    /**
     * Destroys faction
     */
    private function destroyFaction()
    {
        $this->game->destroyFaction($this->getParameterValue("factionId"));
    }

    /**
     * Checks if exist synchronous request and acceptation of faction entrance
     */
    private function makeFactionRequest()
    {
        $factionId = $this->getParameterValue("factionId");
        $characterId = $this->getParameterValue("characterId");
        $leaderPositions = $this->game->getFaction($factionId)->getLeader()->getRequest()->getPositions();
        foreach($leaderPositions as $position){
            if($position->checkFactionRequestAccept($characterId)){
                $character = $this->game->getCharacter($characterId);
                $character->setFactionId($factionId);
                break;
            }
        }
    }

    /**
     * Creates new Army
     */
    private function createArmy()
    {
        $this->game->createArmy(
            [
                "name" => $this->getParameterValue("name"),
                "officerId" => $this->getParameterValue("officerId"),
                "strength" => $this->getParameterValue("strength")
            ]
        );
    }

    /**
     * Destroys Army
     */
    private function destroyArmy()
    {
        $this->game->destroyArmy($this->getParameterValue("armyId"));
    }
}