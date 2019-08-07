<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 14.04.19
 * Time: 18:38
 */

namespace Drupal\erwartungscheck\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\erwartungscheck\Data\ErwartungscheckData;
use Drupal\erwartungscheck\Helper\ErwartungscheckHelper;
use Drupal\node\Entity\Node;
use Drupal\erwartungscheck\StringGenerator\StringGenerator;

class ErwartungscheckController extends ControllerBase {

  public function erwartungscheckInfo($percent) {

    $percentErreicht = $percent;

    $userData = new ErwartungscheckData();
    $userString = $userData->randomString();

    //$generator = new StringGenerator();
    //$neuerString = $generator->randomString();
    //dsm($neuerString);

     //Je nach erreichter Prozentzahl, wird entsprechend der Text mit den erreichten Prozent ausgegeben.
     if($percentErreicht < 25) {
       return [
         '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind nicht sehr realistisch.</p>
                        <p>Hier Ihr Code [' . $userString . ']</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
       ];
    } elseif ($percentErreicht >= 25 && $percentErreicht < 50) {
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind in Teilen realistisch.</p>
                        <p>Hier Ihr Code [' . $userString . ']</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
      ];
    } elseif ($percentErreicht >= 50 && $percentErreicht < 75) {
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind weitestgehend realistisch.</p>
                        <p>Hier Ihr Code [' . $userString . ']</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
      ];
    } elseif ($percentErreicht >= 75 && $percentErreicht < 100) {
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind sehr realistisch.</p>
                        <p>Hier Ihr Code [' . $userString . ']</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
      ];
    } elseif ($percentErreicht == 100) {
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind komplett realistisch.</p>
                        <p>Hier Ihr Code [' . $userString . ']</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
      ];
    }

     /**
    return [
      '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind sehr realistisch.</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
    ];
      */
  }

  public function erwartungscheckQuiz() {
    // TODO remove function and corresponding routing
    //Pruefe welcher Studiengang
    $studiengang = 32; // "Erwartungscheck Wirtschaftsinformatik"
    //TODO Lade Fragen

    //TODO Gebe Fragen in einer Form aus
    $form = \Drupal::formBuilder()->getForm('Drupal\erwartungscheck\Form\ErwartungscheckForm', $studiengang);
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

      $targetId = $studiengang->field_erwartungscheck->target_id;

      $form = \Drupal::formBuilder()->getForm('Drupal\erwartungscheck\Form\ErwartungscheckForm', $targetId);
      return $form;
    }
  }
}