<?php
namespace diplomacy\modules\vestria\controllers;

use diplomacy\modules\vestria\components\VesController;
use diplomacy\modules\vestria\models\CharacterAction;
use diplomacy\modules\vestria\models\RequestPosition;

/**
 * Class TurnController
 *
 * Контроллер для управления обсчетом хода
 */
class TurnController extends VesController
{
    /**
     * Запуск обсчета хода
     */
    public function actionGo()
    {
        $this->makeTurn();

        $this->redirect($this->createUrl( 'game/' ));
    }

    /**
     * Управление обсчетом хода
     */
    private function makeTurn()
    {
        $this->preprocessTurn();
        // общие заявки
        $this->applicateRequests([CharacterAction::TYPE_CHARACTERS]);
        // приход в бюджет
        $this->budgetCalculation();
        // траты и набор войск
        $this->applicateRequests([CharacterAction::TYPE_SPENDING]);
        // движения армий и сражения
        $this->applicateRequests([CharacterAction::TYPE_MANEUVRES]);

        $this->checkAmbitions();

        $this->postprocessTurn();


    }

    private function preprocessTurn()
    {
        $this->game->setTurn($this->game->getTurn() + 1);
        $this->gameModel->last_turn += 1;
        $this->game->randomizeCharactersOrder();
    }

    private function postprocessTurn()
    {
        $this->game->clearRequests();
        $this->game->save();
        $this->gameModel->save();
    }


    /**
     * Применение позиций заявок заданных типов
     * @param int[] $types
     */
    private function applicateRequests($types = [])
    {
       // \CVarDumper::dump(json_encode($this->game), 3, 1);
        foreach($this->game->getCharacters() as $character) {
            $request = $this->game->getRequestByCharacterId($character->getId());
            $positions = [];
            if(is_object($request))
                $positions = $request->getPositions(['action.type' => $types]);

            //\CVarDumper::dump($positions, 1, 1);
            /** @var RequestPosition $position */
            foreach($positions as $position){
                $position->apply();
            }

        }
        \CVarDumper::dump(json_encode($this->game), 3, 1);
        die();
    }

    /**
     * Расчет рутинных доходов и расходов бюджета
     */
    private function budgetCalculation()
    {

    }

    private function checkAmbitions()
    {

    }
}