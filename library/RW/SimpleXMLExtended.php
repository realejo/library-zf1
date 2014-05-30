<?php
/**
 * Adiciona a CDATA ao SimpleXML
 *
 * @category   RW
 * @package    RW_SimpleXMLExtended
 * @author     Realejo
 * @version    $Id: SimpleXMLExtended.php 7 2012-01-11 17:15:57Z rodrigo $
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.realejo.com.br)
 *
 * @uses 	   SimpleXMLElement
 */
class RW_SimpleXMLExtended extends SimpleXMLElement
{
  public function addCData($name, $value)
  {
    // Inclui o nó
    $SimpleXMLNode = $this->addChild($name);

    // Cria o elemento no DOM a partir do nó criado e inclui o CDATA
    $DOMNode = dom_import_simplexml($SimpleXMLNode);
    $node = $DOMNode->ownerDocument;
    $DOMNode->appendChild($node->createCDATASection($value));

    // Retorna para o nó no SimpleXML
    $SimpleXMLNode = simplexml_import_dom($DOMNode);

    return $SimpleXMLNode;
  }
}