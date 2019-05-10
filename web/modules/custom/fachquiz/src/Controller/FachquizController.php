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
use Drupal\fachquiz\Helper\FachquizHelper;

class FachquizController extends ControllerBase {

  public function content() {


    //Data
    $fachquizData = new FachquizData();

    //dsm($fachquizData->getData()[0]['aufgabe']);



    return ['#markup' => 'Fachquiz'];

  }

  public function auswertung() {
    return['#markup' => 'Vielen Dank, dass Sie mitgemacht haben. Ihre Auswertung wird hier auch erscheinen,
      wir entwickeln diese Komponente gerade...'];
  }

  public function helper() {
    $fachzquizHelper = new FachquizHelper();
    $fachquiz_data = $fachzquizHelper->getFachquiz();
    dsm($fachquiz_data);
    return ['#markup' => 'Helper'];
  }



}