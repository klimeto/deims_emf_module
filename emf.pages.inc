<?php

/**
 * @file
 * Page callbacks for the EMF module.
 */

/**
 * Output a node as EMF.
 *
 * Sets the content header for xml, renders the EMF, and returns nothing in
 * order to not have render actions take place on the iso.
 * When nothing is returned from a page callback Drupal will not render anything
 * and still call the rest of the proper hooks including necessary exit hooks.
 * For a similar usage example see:
 * @see http://api.drupal.org/api/drupal/modules%21node%21node.module/function/node_feed/7
 */
function emf_output_node($node) {
  if ($node->type != 'site') {
    return MENU_NOT_FOUND;
  }

  try {
    // Get the EMF as a string and output it.
    $site = new emf($node);
    $semf = $site->getemf();
    drupal_add_http_header('Content-Type', 'application/xml; charset=utf-8');
    print $semf;
  }
  catch (Exception $exception) {
    watchdog_exception('emf', $exception);
    return MENU_NOT_FOUND;
  }
}
