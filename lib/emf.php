<?php

/**
 * @file
 * Contains EMF.
 */

/**
 * Utility and API functions for interacting with sites and their EMF.
 */
class emf {

  private $node;

  protected $semf = NULL;

  public function __construct($node) {
    if ($node->type != 'site') {
      throw new Exception('Cannot create an emf object using a node type != emf.');
    }
    $this->node = $node;
  }

  public function getNode() {
    return $this->node;
  }

  public static function getInstance($node) {
    $instances = &drupal_static('emf_instances', array());
    if ($node->type != 'emf') {
      throw new InvalidArgumentException('Cannot create an emf object using a node type != emf.');
    }
    if (empty($node->nid)) {
      return new self($node);
    }
    elseif (!isset($instances[$node->nid])) {
      $instances[$node->nid] = new self($node);
    }

    return $instances[$node->nid];
  }

  /**
   * Render a site into its EMF
   *
   * @return string
   *   A string containing the site's EMF/XML.
   */
  public function getemf($reset = FALSE) {
    if (empty($this->emf) || $reset) {
      $build = node_view($this->node, 'emf');
      $this->emf = render($build);
      $this->emf = $this->tidyXml($this->emf);
    }
    return $this->emf;
  }

  /**
   * Cleanup XML output using the Tidy library
   *
   * @param string $xml
   *   A string containing XML.
   *
   * @return string
   *   The XML after being repaired with Tidy.
   */
  private function tidyXml($xml) {
    if (extension_loaded('tidy')) {
      $config = array(
        'indent' => TRUE,
        'input-xml' => TRUE,
        'output-xml' => TRUE,
        'wrap' => FALSE,
      );
      $tidy = new tidy();
      return $tidy->repairString($xml, $config);
    }
    else {
      // If the Tidy library isn't found, then we can pretty much duplicate
      // the whitespace and indentation cleanup using the PHP DOM library.

      // Need to convert encoded spaces to character encoding.
      $xml = str_replace('&nbsp;', '&#160;', $xml);

      $dom = new DOMDocument();
      $dom->preserveWhiteSpace = FALSE;
      $dom->loadXML($xml);
      $xpath = new DOMXPath($dom);
      foreach ($xpath->query('//text()') as $domNode) {
        $domNode->data = trim($domNode->nodeValue);
      }
      $dom->formatOutput = TRUE;
      return $dom->saveXML($dom->documentElement, LIBXML_NOEMPTYTAG);
    }
  }

}
