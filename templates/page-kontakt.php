<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 * Template Name: Kontakt
 * Description: Kontakt
 */

$templates = array('pages/contact.twig');
$context = Timber::get_context();
$context['post'] = new TimberPost();

Timber::render( $templates, $context );