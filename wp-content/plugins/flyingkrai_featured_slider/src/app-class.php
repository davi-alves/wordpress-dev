<?php

namespace Flyingkrai\FeaturedSlider;

/**
 * Featured Slider
 *
 * @package   Flyingkrai
 * @subpackage FeaturedSlider
 * @author    Davi Alves <davi.alves@indexdigital.com.br>
 * @license   GPL-2.0+
 * @copyright 2013 Davi Alves
 */

/**
 * FeaturedSlider class
 *
 * @package Flyingkrai
 * @subpackage FeaturedSlider
 * @author    Davi Alves <davi.alves@indexdigital.com.br>
 */
class FeaturedSlider
{

  /**
   * @var FeaturedSlider
   */
  protected static $instance = null;

  protected function __construct()
  {
    // init stuff
  }

  /**
   * Get FeaturedSlider singleton
   *
   * @return FeaturedSlider
   */
  public static function getInstance()
  {
    if (null === self::$instance) {
      self::$instance = new self;
    }

    return self::$instance;
  }

}

// add static registration methods
// add static class instance method
// add plugin initialization in cosntructor
// expose public methods
