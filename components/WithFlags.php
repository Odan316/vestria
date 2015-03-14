<?php
/**
 */
namespace diplomacy\modules\vestria\components;


interface WithFlags {
    /**
     * @param string $name
     */
    public function setFlag($name);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasFlag($name);

    /**
     * @param string $name
     *
     * @return void
     */
    public function removeFlag($name);
}