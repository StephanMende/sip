<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 14.04.19
 * Time: 18:38
 */

namespace Drupal\erwartungscheck\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\erwartungscheck\Helper\ErwartungscheckHelper;
use Drupal\node\Entity\Node;

class ErwartungscheckController extends ControllerBase {

  public function erwartungscheckInfo($percent) {
    return [
      '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind sehr realistisch.</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
    ];
  }

  public function erwartungscheckQuiz() {
    //Pruefe welcher Studiengang
    $studiengang = "Wirtschaftsinformatik"; //TODO Parameterisieren
    //TODO Lade Fragen

    //TODO Gebe Fragen in einer Form aus
    $form = \Drupal::formBuilder()->getForm('Drupal\erwartungscheck\Form\ErwartungscheckForm');
    return $form;
  }

  public function erwartungscheckHelp() {
    $erwartungscheckHelper = new ErwartungscheckHelper();
    $aussagenData = $erwartungscheckHelper->getErwartungcheck();

    dsm($aussagenData);
    return ['#markup' => 'Hallo....'];
  }




  public function zeigeCheck($check) {

    $nids = \Drupal::entityQuery('node')
      ->condition('type','studiengang')->condition('title', $check)->execute();


    $studiengaenge = Node::loadMultiple($nids);


    foreach ($studiengaenge as $studiengang) {
      $studiengang->field_erwartungscheck->target_id;
      ksm($studiengang->field_erwartungscheck->target_id);

      $targetId = $studiengang->field_erwartungscheck->target_id;

      $form = \Drupal::formBuilder()->getForm('Drupal\erwartungscheck\Form\ErwartungscheckForm', $targetId);
      return $form;
    }






    $array = [
      '#type' => 'markup',
      '#markup' => '<b>Erwartungscheck:</b>',
    ];

    switch ($check) {

      case 1:
        $array = [
          '#type' => 'markup',
          '#markup' => '<b>Erwartungscheck WINF</b>',
        ];
        return $array;
      case 2:
        $array = [
          '#type' => 'markup',
          '#markup' => '<b>Erwartungscheck 2</b>',
        ];
        return $array;
      case 3:
        $array = [
          '#type' => 'markup',
          '#markup' => '<b>Erwartungscheck 3</b>',
        ];
        return $array;
      case 4:
        $array = [
          '#type' => 'markup',
          '#markup' => '<b>Erwartungscheck 4</b>',
        ];
        return $array;
      default:
        return $array;
    }
  }
}