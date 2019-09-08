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
use Drupal\node\Entity\Node;


class FachquizController extends ControllerBase {

  public function content() {

    //Data
    //$fachquizData = new FachquizData();

    //dsm($fachquizData->getData()[0]['aufgabe']);

    return ['#markup' => 'Fachquiz'];

  }

  public function auswertung() {

    $userData = new FachquizData();
    $userString = $userData->randomString();

    return['#markup' => 'Vielen Dank, dass Sie mitgemacht haben. Ihre Auswertung wird hier auch erscheinen,
      wir entwickeln diese Komponente gerade... Ihr Bewerbungscode: ' . $userString . ' '];
  }

  public function helper() {
    $fachzquizHelper = new FachquizHelper();
    $fachquiz_data = $fachzquizHelper->getFachquiz();
    dsm($fachquiz_data);
    return ['#markup' => 'Helper'];
  }

/*
    //SOll, wenn mehrere Fachquizes vorhanden sind auf eine Auswahlseite weiter leite, von der sich dann
    // der Mitarbeiter die Links holen kann
     public function zeigeCheckfq($checkfq) {

        $nids = \Drupal::entityQuery('node')
            ->condition('type','studiengang')->condition('title', $checkfq)->execute();

        $studiengaenge = Node::loadMultiple($nids);


        foreach ($studiengaenge as $studiengang) {
            $studiengang->field_fachquiz->target_id;
            //kint($studiengang);

            $targetId = $studiengang->field_fachquiz->target_id;
            if(count($studiengang->field_fachquiz) === 1){
            $form = \Drupal::formBuilder()->getForm('Drupal\fachquiz\Form\fachquizForm', $targetId);
            return $form;}
            else{
                print target_id;

            }
        }
    }*/
}