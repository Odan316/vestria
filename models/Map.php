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

    public function createMapAsSVG()
    {
        $XML = new DOMDocument();
        $svgNode = $XML->createElement("svg");
        foreach($this->svg as $SVGAttr => $SVGValue){
            if($SVGAttr != "g"){
                $svgNode->setAttribute($SVGAttr, $SVGValue);
            } else {
                foreach($SVGValue as $g){
                    $gNode = $XML->createElement("g");
                    foreach($g as $gAttr => $gVal){
                        if($gAttr != "path"){
                            $gNode->setAttribute($gAttr, $gVal);
                        } else {
                            foreach($gVal as $path){
                                $pathNode = $XML->createElement("path");
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
        $XML->appendChild($svgNode);
        return $XML->saveXML();
    }

}