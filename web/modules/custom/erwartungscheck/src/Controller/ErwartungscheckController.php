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
use Drupal\stringgenerator\StringGenerator\StringGenerator;

class ErwartungscheckController extends ControllerBase {

  public function erwartungscheckInfo($percent) {

    $percentAch = $this->percent;

    //TODO String in der DB speichern bzw. an die Session anhängen, damit der User bei seinem Bewerbungsprozess darauf zugreifen kann.
    $userResponseString = new StringGenerator();
    $userResponseString.randomString();

     //Je nach erreichter Prozentzahl, wird entsprechend der Text mit den erreichten Prozent ausgegeben.
     if($percentAch < 25) {
       return [
         '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind nicht sehr realistisch.</p>
                        <p>Nutzen Sie diesen Code, später bei Ihrer Bewerbung: [' . $userResponseString .']</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
       ];
       dsm('sehr schlecht');
    } elseif ($percentAch >= 25 && $percentAch < 50) {
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind in Teilen realistisch.</p>
                        <p>Nutzen Sie diesen Code, später bei Ihrer Bewerbung: [' . $userResponseString .']</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
      ];
       dsm('schlecht');
    } elseif ($percentAch >= 50 && $percentAch < 75) {
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind weitestgehend realistisch.</p>
                        <p>Nutzen Sie diesen Code, später bei Ihrer Bewerbung: [' . $userResponseString .']</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
      ];
       dsm('mittel');
    } elseif ($percentAch >= 75 && $percentAch < 100) {
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind sehr realistisch.</p>
                        <p>Nutzen Sie diesen Code, später bei Ihrer Bewerbung: [' . $userResponseString .']</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten 
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
      ];
       dsm('gut');
    } elseif ($percentAch == 100) {
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen 
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind komplett realistisch.</p>
                        <p>Nutzen Sie diesen Code, später bei Ihrer Bewerbung: [' . $userResponseString .']</p>
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