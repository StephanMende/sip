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

  public function erwartungscheckInfo($check, $percent) {

    $percentErreicht = $percent;

    $userData = new ErwartungscheckData();
    $userString = $userData->randomString();

    $node_storage = \Drupal::entityTypeManager()->getStorage('node');

    /*
     * Auslesen der Felder für die Erwartungscheck Infotexte. Diese werden bei der Erstellung eines Studienganges mit
     * angelegt und mit dem gewuenschten Text befuellt. Hier funktioniert es nur für maximal fuenf Felder. Bisher wird
     * zwar je nach Anzahl der Infotexte in den richtigen if-Block gesprungen, allerdings wird der Feldwert nicht ausgegeben.
     */
    $query = \Drupal::entityQuery('node')->condition('type', 'studiengang')->condition('title', $check);
    $nids = $query->execute();
    $nodes = $node_storage->loadMultiple($nids);

    foreach ($nodes as $n) {
      $ausgabeText = array($n->field_ausgabeerwartungscheck->value);
      //$ausgabeText [] = $n[array($n->field_ausgabeerwartungscheck->value)];
    }

    //Wenn nur ein Feld angelegt wurde, wird der Wert hier ausgegeben.
    //Test
    if (count($n->field_ausgabeerwartungscheck) == 1) {

      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind nicht sehr realistisch.</p>
                        <p>Hier Ihr Code [' . $userString . ']</p> 
                        <p>Hier Ihr Text [' . $ausgabeText[0] . ']</p>',
      ];

      //Wenn zwei Felder angelegt wurden, soll je nach erreichter Prozentzahl das passende Feld ausgegeben werden.
    } else if (count($n->field_ausgabeerwartungscheck) == 2) {

        $bereich = 0;

        if ($percentErreicht > 50) {
            $bereich = 1;
        }

        return [
          '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind nicht sehr realistisch.</p>
                        <p>Hier Ihr Code [' . $userString . ']</p>
                        <p>Hier Ihr Text [' . $ausgabeText[$bereich] . ']</p> 
                        <p>Hier Ihr Text Hallo2</p>',
        ];

        //Analog fuer drei Felder
    } else if (count($n->field_ausgabeerwartungscheck) == 3) {

        $bereich = 0;

        if ($percentErreicht > 33 && $percentErreicht <= 66) {
        $bereich = 1;
        }

        else if ($percentErreicht > 66) {
          $bereich = 2;
        }

        return [
          '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen
                                von Studierenden und Lehrenden aus dem Fachbereich überein und sind nicht sehr realistisch.</p>
                                <p>Hier Ihr Code [' . $userString . ']</p> 
                                <p>Hier Ihr Text [' . $ausgabeText[$bereich] . ']</p>
                                <p>Hier Ihr Text Hallo3</p>',
        ];

      //Analog fuer vier Felder
    } else if (count($n->field_ausgabeerwartungscheck) == 4) {

      $bereich = 0;

      if ($percentErreicht > 25 && $percentErreicht <= 50) {
        $bereich = 1;
      }

      else if ($percentErreicht > 50 && $percentErreicht <= 75) {
        $bereich = 2;
      }

      else if ($percentErreicht > 75) {
        $bereich = 3;
      }


      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen
                                von Studierenden und Lehrenden aus dem Fachbereich überein und sind nicht sehr realistisch.</p>
                                <p>Hier Ihr Code [' . $userString . ']</p> 
                                <p>Hier Ihr Text [' . $ausgabeText[$bereich] . ']</p>
                                <p>Hier Ihr Text Hallo4</p>',
      ];

      //Analog fuer fuenf Felder
    } else if (count($n->field_ausgabeerwartungscheck) == 5) {

      $bereich = 0;

      if ($percentErreicht > 20 && $percentErreicht <= 40) {
        $bereich = 1;
      }

      else if ($percentErreicht > 40 && $percentErreicht <= 60) {
        $bereich = 2;
      }

      else if ($percentErreicht > 60 && $percentErreicht <= 80) {
        $bereich = 3;
      }

      else if ($percentErreicht > 80) {
        $bereich = 4;
      }

      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen
                                von Studierenden und Lehrenden aus dem Fachbereich überein und sind nicht sehr realistisch.</p>
                                <p>Hier Ihr Code [' . $userString . ']</p> 
                                <p>Hier Ihr Text [' . $ausgabeText[$bereich] . ']</p>
                                <p>Hier Ihr Text Hallo5</p>',
      ];

    } else {
      return [
        '#markup' => '<p>Falls nichts zutrifft</p>',
      ];
    }

    //Je nach erreichter Prozentzahl, wird entsprechend der Text mit den erreichten Prozent ausgegeben.
/*    if ($percentErreicht < 25) {

      //Lade Node wo das Feld field_infortexterwartung = AussageInforErwWINF vom Content Type bloeder Name
      //Führe Query aus
      //$nids = $query-execute
      //Lade Node Objekte
      //$nodes = $node_storage-loadMultiple($nids)
      //Iteriere über ndes und speicher die Daten in einer Variable
      //Gebe den Wert -textfield_name-value aus

      /*
      $node_storage = \Drupal::entityTypeManager()->getStorage('node');

      $query = \Drupal::entityQuery('node')->condition('type', 'studiengang')->condition('title', $check);
      $nids = $query->execute();
      $nodes = $node_storage->loadMultiple($nids);

      foreach ($nodes as $n) {
        $ausgabeText = $n->field_ausgabeerwartungscheck->value;
        kint(count($n->field_ausgabeerwartungscheck));

        //foreach ($n->field_ausgabeerwartungscheck as $elements) {
      //}
      kint($n);
    }
      */
    /*
    return [
      '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind nicht sehr realistisch.</p>

                        <p>Hier der Text der Aussage:  [' . $ausgabeText . '] </p>

                        <p>Hier Ihr Code [' . $userString . ']</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
    ];
  } elseif ($percentErreicht >= 25 && $percentErreicht < 50) {

return ['#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind in Teilen realistisch.</p>
                        <p>Hier Ihr Code [' . $userString . ']</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',];

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
    } elseif ($percentErreicht >= 75 && $percentErreicht < 100){
      return [
        '#markup' => '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen
                        von Studierenden und Lehrenden aus dem Fachbereich überein und sind sehr realistisch.</p>
                        <p>Hier Ihr Code [' . $userString . ']</p>
                        <p><em>Wenn Sie sich noch weiter informieren möchten, dann schauen Sie sich die <a href="#">Video-Interviews</a>
                        hier im SIP-Portal an. Die <a href="#">Webseite</a> der Universität Hildesheim bietet weitere Informationen an.</em></p>
                        <p><em>Die Fächer Mathe, Informatik und Wirtschaft spielen eine wichtige Rolle. Wie gut Sie mit den fachlichen Inhalten
                        zurechtkommen, können Sie <a href="#">hier</a> in den verschiedenen Fach-Quizzes testen.</em></p>',
      ];
    } elseif ($percentErreicht == 100){
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

      kint($check);

      $form = \Drupal::formBuilder()->getForm('Drupal\erwartungscheck\Form\ErwartungscheckForm', $targetId, $check);
      return $form;
    }
  }
}
