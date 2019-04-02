<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 26.03.19
 * Time: 18:49
 */

namespace Drupal\fachquiz\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\fachquiz\Data\FachquizData;

class FachquizController extends ControllerBase {

  public function content() {


    //Data
    $fachquizData = new FachquizData();

    dsm($fachquizData->getData()[0]['aufgabe']);



    return ['#markup' => 'Fachquiz'];

  }



}