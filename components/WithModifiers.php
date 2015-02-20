<?php
/**
 */
namespace diplomacy\modules\vestria\components;

use diplomacy\modules\vestria\models\Modifier;


interface WithModifiers {
    /**
     * @param Modifier $modifier
     */
    public function setModifier($modifier);
}