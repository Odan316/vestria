<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class Request
 *
 * Класс заявки игрока
 */
class Request extends \JSONModel
{
    /** @var int */
    protected $characterId;

    /** @var RequestPosition[] */
    protected $positions = [];

    /** @var int */
    private $lastPositionId = 0;

    /** @var Game */
    protected $game;
    /** @var Character */
    protected $character;

    /**
     * @param Game $game
     * @param [] $data
     */
    public function __construct( $game, $data = [ ] )
    {
        $this->game = $game;
        parent::__construct( $data );
    }

    /**
     * @inheritdoc
     */
    public function setAttributes($data)
    {
        if(isset($data['positions'])){
            foreach($data['positions'] as $positionData){
                if(isset($positionData['id']) && $this->getPosition($positionData['id'])){
                    $this->updatePosition($positionData);
                } else {
                    $this->createPosition($positionData);
                }
            }
            unset($data['positions']);
        }
        parent::setAttributes($data);
    }

    /**
     * @param int $characterId
     *
     * @return Request
     */
    public function setCharacterId($characterId)
    {
        $this->characterId = $characterId;
        return $this;
    }
    /**
     * @return int
     */
    public function getCharacterId()
    {
        return $this->characterId;
    }

    /**
     * @return Character|null
     */
    public function getCharacter()
    {
        if (empty( $this->character )) {
            $this->character = $this->game->getCharacter( $this->characterId );
        }
        return $this->character;
    }

    /**
     * @return RequestPosition[]
     */
    public function getPositions($criteria = [], $as_array = false)
    {
        $models = [];

        foreach($this->positions as $model){
            if($model->testCriteria($criteria)) $models[] = $model;
        }

        if ( ! $as_array) {
            return $models;
        } else {
            return $this->makeList($models);
        }
    }

    /**
     * @param int $id
     *
     * @return RequestPosition|null
     */
    public function getPosition($id)
    {
        foreach($this->positions as $position) {
            if($position->getId() == $id)
                return $position;
        }
        return null;
    }

    public function createPosition($data)
    {
        $this->lastPositionId ++;
        $data['id'] = $this->lastPositionId;
        $model = new RequestPosition($this, $data);
        $this->positions[] = $model;
    }

    /**
     * @param [] $data
     *
     * @return void
     */
    public function updatePosition($data)
    {
        $position = $this->getPosition($data['id']);
        if($position){
            $position->setAttributes($data);
        }
    }

    /**
     * @param int $id
     */
    public function deletePosition($id)
    {
        foreach($this->positions as $key => $position){
            if($position->getId() == $id){
                unset($this->positions[$key]);
                $this->game->save();
                break;
            }
        }
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "characterId" => $this->characterId,
            "positions"   => $this->positions
        ];
    }
}