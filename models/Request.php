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
    protected $positions;

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
        if(isset($data['positions'])){
            foreach($data['positions'] as $positionData){
                $this->positions = new RequestPosition($positionData);
            }
            unset($data['positions']);
        }
        parent::__construct( $data );
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
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }
}