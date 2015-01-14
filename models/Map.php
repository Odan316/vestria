<?php

/**
 * Class Map
 *
 * Класс строящий карту
 */
class Map extends JSONModel
{
    /** @var [] */
    protected $defaults;

    /** @var [] */
    protected $svg;

    /** @var DOMDocument */
    private $XML;

    /** @var Game */
    private $game;

    /**
     * @param Game $game
     * @param [] $data
     */
    public function __construct( $game, $data = [ ] )
    {
        $this->game = $game;
        parent::__construct( $data );
        $this->createMap();
        $this->implementTurnData();
    }

    /**
     * Возвращает строку с кодом карты для вставки в HTML
     *
     * @return void
     */
    public function createMap()
    {
        $this->XML = new DOMDocument();
        $svgNode   = $this->XML->createElement( "svg" );
        $svgNode->setAttribute( "id", "mapSVG" );
        $svgNode->setIdAttribute( 'id', true );
        foreach ($this->svg as $SVGAttr => $SVGValue) {
            if ( ! in_array( $SVGAttr, [ "g" ] )) {
                $svgNode->setAttribute( $SVGAttr, $SVGValue );
            }
        }
        $width  = $this->defaults["width"];
        $height = $width / $this->defaults["ratio"];
        $svgNode->setAttribute( "width", $width . "px" );
        $svgNode->setAttribute( "height", $height . "px" );
        foreach ($this->svg["g"] as $g) {
            $gNode = $this->XML->createElement( "g" );
            foreach ($g as $gAttr => $gVal) {
                if ($gAttr != "path") {
                    $gNode->setAttribute( $gAttr, $gVal );
                    if ($gAttr == "id") {
                        $gNode->setIdAttribute( 'id', true );
                    }
                } else {
                    foreach ($gVal as $path) {
                        $pathNode = $this->XML->createElement( "path" );
                        foreach ($path as $pathAttr => $pathVal) {
                            $pathNode->setAttribute( $pathAttr, $pathVal );
                            if ($pathAttr == "id") {
                                $pathNode->setIdAttribute( 'id', true );
                            }
                        }
                        $gNode->appendChild( $pathNode );
                    }
                }
            }
            $svgNode->appendChild( $gNode );
        }
        $this->XML->appendChild( $svgNode );
    }

    /**
     * Накладывает на базовую карту данные хода:
     * цвета фракций, названия провинций
     *
     * @return void
     */
    public function implementTurnData()
    {
        $provincesData = $this->game->getProvinces();
        foreach ($provincesData as $province) {
            $provNode  = $this->XML->getElementById( "map_province_fill_" . $province->getId() );
            $provColor = $province->getOwner() ? $province->getOwner()->getColor() : $this->defaults["province_fill"];
            if ($province->getOwner()) {
                $provNode->attributes->getNamedItem( "fill" )->nodeValue = $provColor;
            }
            $provincesNode = $this->XML->getElementById( "provinces_fill" );

            $textNode = $this->XML->createElement( "text", $province->getName() );
            $textNode->setAttribute( "x", $province->getNameX() );
            $textNode->setAttribute( "y", $province->getNameY() );
            if ($province->getNameSize()) {
                $textNode->setAttribute( "style", "font-size:" . $province->getNameSize() . ";" );
            }
            $textNode->setAttribute( "fill", $this->getContrastYIQ( substr( $provColor, 1, 7 ) ) );
            $provincesNode->appendChild( $textNode );
        }

    }

    /**
     * Возвращает строку, содержащую код карты в формате SVG
     * @param int|null $width
     *
     * @return string
     */
    public function getSVG( $width = null )
    {
        if ($width != null) {
            $svgNode                                                  = $this->XML->getElementById( "mapSVG" );
            $height                                                   = $width / $this->defaults["ratio"];
            $svgNode->attributes->getNamedItem( "width" )->nodeValue  = $width . "px";
            $svgNode->attributes->getNamedItem( "height" )->nodeValue = $height . "px";
        }
        return $this->XML->saveXML();
    }

    /**
     * Возвращает цвет текста, контрастный цвету фона
     * @param string $hexcolor Цвет фона
     *
     * @return string
     */
    private function getContrastYIQ($hexcolor){
        $r = hexdec(substr($hexcolor,0,2));
        $g = hexdec(substr($hexcolor,2,2));
        $b = hexdec(substr($hexcolor,4,2));
        $yiq = (($r*299)+($g*587)+($b*114))/1000;
        return ($yiq >= 128) ? 'black' : 'white';
    }

}