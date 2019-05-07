<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 02.04.19
 * Time: 18:53
 */

namespace Drupal\bwl_test\Controller;

use Drupal\Core\Controller\ControllerBase;

class BWLTestController extends ControllerBase {

  public function content() {
    return ['#markup' => 'BWL Test'];
  }
}