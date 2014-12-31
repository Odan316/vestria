<?php
/**
 * Created by PhpStorm.
 * User: onag
 * Date: 31.12.14
 * Time: 14:58
 */

class CharacterClass {

    /** @var  int */
    private $id;
    /** @var  string */
    private $name;

    /**
     * @param [] $data
     */
    public function __construct($data)
    {
        foreach($data as $param => $value){
            $this->$param = $value;
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}