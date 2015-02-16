<?php
/**
 */
namespace diplomacy\modules\vestria\components;


interface WithFlags {
    /**
     * @param string $name
     * @param bool $value
     */
    public function setFlag($name, $value);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasFlag($name);
}