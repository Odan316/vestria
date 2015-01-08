<?php

/**
 * Class Map
 *
 * Класс строящий карту
 */

class Map extends JSONModel
{
    /** @var [] */
    protected $svg;

    /** @var DOMDocument */
    private $XML;

    /**
     * @param Game $game
     * @param [] $data
     */
    public function __construct( $game, $data = [ ] )
    {
        $this->game = $game;
        //$this->svg = $data['svg'];
        parent::__construct($data);
        $this->createMap();
    }

    /**
     * Возвращает строку с кодом карты для вставки в HTML
     *
     * @return void
     */
    public function createMap()
    {
        $this->XML = new DOMDocument();
        $svgNode = $this->XML->createElement("svg");
        foreach($this->svg as $SVGAttr => $SVGValue){
            if($SVGAttr != "g"){
                $svgNode->setAttribute($SVGAttr, $SVGValue);
            } else {
                foreach($SVGValue as $g){
                    $gNode = $this->XML->createElement("g");
                    foreach($g as $gAttr => $gVal){
                        if($gAttr != "path"){
                            $gNode->setAttribute($gAttr, $gVal);
                        } else {
                            foreach($gVal as $path){
                                $pathNode = $this->XML->createElement("path");
                                foreach($path as $pathAttr => $pathVal){
                                    $pathNode->setAttribute($pathAttr, $pathVal);
                                }
                                $gNode->appendChild($pathNode);
                            }
                        }
                    }
                    $svgNode->appendChild($gNode);
                }
            }
        }
        $this->XML->appendChild($svgNode);
    }

    /**
     * @param []Province $provinceData
     */
    public function implementTurnData($provinceData)
    {

    }

    public function getSVG()
    {
        return $this->XML->saveXML();
    }

}