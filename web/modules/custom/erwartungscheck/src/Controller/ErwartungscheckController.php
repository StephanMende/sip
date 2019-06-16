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

     //Je nach erreichter Prozentzahl, wird entsprechend der Text mit den erreichten Prozent ausgegeben.
     if($percent < 25) {
       return [
         '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind nicht sehr realistisch.</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
       ];
       dsm('sehr schlecht');
    } else if ($percent >= 25 && $percent < 50) {
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind in Teilen realistisch.</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
      ];
       dsm('schlecht');
    } else if ($percent >= 50 && $percent < 75) {
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind weitestgehend realistisch.</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
      ];
       dsm('mittel');
    } else if ($percent >= 75 && $percent < 100) {
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind sehr realistisch.</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
      ];
       dsm('gut');
    } else if ($percent == 100) {
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind komplett realistisch.</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
      ];
       dsm('sehr gut');
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
