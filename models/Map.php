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
        $svgNode->setAttribute("id", "mapSVG");
        foreach($this->svg as $SVGAttr => $SVGValue){
            if(!in_array($SVGAttr, ["width", "ratio", "g"])){
                $svgNode->setAttribute($SVGAttr, $SVGValue);
            }
            $width = $this->svg["width"];
            $height = $width/$this->svg["ratio"];
            $svgNode->setAttribute("width", $width."px");
            $svgNode->setAttribute("height", $height."px");
            foreach($this->svg["g"] as $g){
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
        $this->XML->appendChild($svgNode);
    }

    /**
     * @param []Province $provinceData
     */
    public function implementTurnData($provinceData)
    {

    }

    /**
     * @param int|null $width
     *
     * @return string
     */
    public function getSVG($width = null)
    {
        if($width != null){
            $svgNode = $this->XML->getElementsByTagName("svg")->item(0);
            $height = $width/$this->svg["ratio"];
            $svgNode->attributes->getNamedItem("width")->nodeValue = $width."px";
            $svgNode->attributes->getNamedItem("width")->nodeValue = $height."px";
        }
        return $this->XML->saveXML();
    }

}