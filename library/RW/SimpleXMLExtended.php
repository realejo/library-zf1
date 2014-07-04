<?php
/**
 * Adiciona a CDATA ao SimpleXML
 *
 * @uses 	   SimpleXMLElement
 *
 * @link      http://github.com/realejo/libraray-zf1
 * @copyright Copyright (c) 2011-2014 Realejo (http://realejo.com.br)
 * @license   http://unlicense.org
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