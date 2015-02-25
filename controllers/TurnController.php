<?php
namespace diplomacy\modules\vestria\controllers;

use diplomacy\modules\vestria\components\ModelsFinder;
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

    const ESTATES_BASE_INCOME = 50;
    const FACTORIES_BASE_INCOME = 25;

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
        $this->applicateRequests([CharacterAction::PHASE_COMMON]);
        // траты и набор войск
        $this->applicateRequests([CharacterAction::PHASE_SPENDING]);
        // движения армий и сражения
        $this->applicateRequests([CharacterAction::PHASE_MANEUVRES]);
        // проверка принадлежности провинций
        $this->provinceCheck();
        // приход в бюджет
        $this->budgetCalculation();
        // движения армий и сражения
        $this->applicateRequests([CharacterAction::PHASE_MANEUVRES]);

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
     * @param int[] $phases
     */
    private function applicateRequests($phases = [])
    {
       // \CVarDumper::dump(json_encode($this->game), 3, 1);
        foreach($this->game->getCharacters() as $character) {
            $request = $this->game->getRequestByCharacterId($character->getId());
            $positions = [];
            if(is_object($request))
                $positions = $request->getPositions(['action.phase' => $phases]);

            /** @var RequestPosition $position */
            foreach($positions as $position){
                $position->apply();
            }

        }
        //\CVarDumper::dump(json_encode($this->game), 3, 1);
        //die();
    }

    /**
     * Расчет рутинных доходов и расходов бюджета
     */
    private function budgetCalculation()
    {
        foreach($this->game->getCharacters() as $character) {
            $estatesModifier = $character->getModifier("estatesIncome");
            $estatesIncome = $character->getEstatesCount() * self::ESTATES_BASE_INCOME * (1+$estatesModifier);
            $factoriesModifier = $character->getModifier("factoriesIncome");
            $factoriesIncome = $character->getFactoriesCount() * self::FACTORIES_BASE_INCOME * (1+$factoriesModifier);
            //\CVarDumper::dump($character->getName()." e".$estatesIncome." em".$estatesModifier." f".$factoriesIncome, 1, 1);
            $character->setCash($character->getCash() + $estatesIncome + $factoriesIncome);
        }
        //die();
    }

    private function provinceCheck()
    {

    }

    private function checkAmbitions()
    {

    }
}