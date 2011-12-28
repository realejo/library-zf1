<?php
/**
 * Adiciona a CDATA ao SimpleXML
 *
 * @category   RW
 * @package    RW_SimpleXMLExtended
 * @author     Realejo $Author$
 * @version    $Id$
 * @copyright  Copyright (c) 2011-2012 Realejo Design Ltda. (http://www.relaejo.com.br)
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