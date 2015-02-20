<?php
namespace diplomacy\modules\vestria\models;

use diplomacy\modules\vestria\components\WithModifiers;
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
    /** @deprecated
     * @var mixed */
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
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
                            $newValue = $this->getValue();
                            break;
                        case "add":
                            $newValue = $propertyValue + $this->getValue();
                            break;
                        case "takeAway":
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
                        $this->createFaction($game);
                        break;
                }
                break;
            case "destroyObject":
                switch($this->object){
                    case "Faction":
                        $this->destroyFaction($game);
                        break;
                }
                break;
            case "factionRequest":
                $this->makeFactionRequest($game);
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

    /**
     * @param Game $game
     */
    private function createFaction($game)
    {
        $game->createFaction(
            [
                "name" => $this->getParameterValue("name"),
                "leaderId" => $this->getParameterValue("leaderId"),
                "color" => $this->getParameterValue("color")
            ]
        );
    }

    /**
     * @param Game $game
     */
    private function destroyFaction($game)
    {
        $game->destroyFaction($this->getParameterValue("factionId"));
    }

    /**
     * @param Game $game
     */
    private function makeFactionRequest($game)
    {
        $factionId = $this->getParameterValue("factionId");
        $characterId = $this->getParameterValue("characterId");
        $leaderPositions = $game->getFaction($factionId)->getLeader()->getRequest()->getPositions();
        foreach($leaderPositions as $position){
            if($position->checkFactionRequestAccept($characterId)){
                $character = $game->getCharacter($characterId);
                $character->setFactionId($factionId);
                break;
            }
        }
    }
}