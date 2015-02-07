<?php
/**
 * Created by PhpStorm.
 * User: onag
 * Date: 30.01.15
 * Time: 8:50
 */

namespace diplomacy\modules\vestria\components;

use diplomacy\modules\vestria\models\Game;
use diplomacy\modules\vestria\models\Character;
use diplomacy\modules\vestria\models\CharacterAmbition;
use diplomacy\modules\vestria\models\CharacterTrait;
use diplomacy\modules\vestria\models\CharacterAction;

class ModelsFinder {
    /** @var Game */
    private $game;

    public function __construct($game)
    {
        $this->game = $game;
    }

    /**
     * @param Character $character
     *
     * @return CharacterAmbition[]
     */
    public function findAmbitions($character)
    {
        $list = [];

        /** @var CharacterAmbition[] $models */
        $models = $this->game->getConfig()->getConfigAsObjectsArray( 'character_ambitions' );
        foreach ($models as $model) {
            if($model->canTake($character))
                $list[] = $model;
        }

        return $list;
    }

    /**
     * @param Character $character
     *
     * @return CharacterTrait[]
     */
    public function findTraits($character)
    {
        $list = [];

        /** @var CharacterTrait[] $models */
        $models = $this->game->getConfig()->getConfigAsObjectsArray( 'character_traits' );
        foreach ($models as $model) {
            if($model->canTake($character))
                $list[] = $model;
        }

        return $list;
    }

    /**
     * @param Character $character
     *
     * @return CharacterAction[]
     */
    public function findActions($character)
    {
        $list = [];

        /** @var CharacterAction[] $models */
        $models = $this->game->getConfig()->getConfigAsObjectsArray( 'character_actions' );
        foreach ($models as $model) {
            if($model->canUse($character))
                $list[] = $model;
        }

        return $list;
    }

    /**
     * @param string $alias
     * @param string|null $filter
     * @param bool $asArray
     *
     * @return null
     */
    public function getObjects($alias, $filter = null, $asArray = false)
    {
        $objects = null;
        $objectPath = explode(".", $alias);
        switch($objectPath[0]){
            case "Province":
                $objects = $this->game->getProvinces([], $asArray);
                break;
            case "Character":
                $objects = $this->game->getCharacters([], $asArray);
                break;
            default:
                break;
        }

        return $objects;
    }
}