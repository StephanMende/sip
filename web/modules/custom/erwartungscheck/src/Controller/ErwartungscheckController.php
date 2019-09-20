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
use Drupal\node\Entity\Node;
use Drupal\erwartungscheck\StringGenerator\StringGenerator;

class ErwartungscheckController extends ControllerBase {


    public function erwartungscheckInfo($check, $percent) {
        //hole den Prozent-Wert aus der URL
        $percent = $percent;
        //Generiere den Random String
        //TODO Flag für Informatikstudiengang einfügen bzw. abgfragen
        $userData = new ErwartungscheckData();
        $userString = $userData->randomString();

        $node_storage = \Drupal::entityTypeManager()->getStorage('node');

        /*
        * Auslesen der Felder für die Erwartungscheck Infotexte. Diese werden bei der Erstellung eines Studienganges mit
        * angelegt und mit dem gewuenschten Text befuellt. Hier funktioniert es nur für maximal fuenf Felder. Bisher wird
        * zwar je nach Anzahl der Infotexte in den richtigen if-Block gesprungen, allerdings wird der Feldwert nicht ausgegeben.
        */
        $query = \Drupal::entityQuery('node')->condition('type', 'studiengang')->condition('title', $check);
        $result = $query->execute();
        //Da entityQuery ein Array zurückgibt und wir den Wert haben wollen hier noch foreach
        foreach ($result as $row) {
          $nid = $row;
        }
        //Lade den Node
        $node = $node_storage->load($nid);
        /**
         * Hier wird über das Feld field_ausgabeerwartungscheck itertiert und alle Wert aus diesem
         * im array $ausgabeText gespeichert.
         */
        foreach($node->field_ausgabeerwartungscheck as $item) {
          $ausgabeText[] = $item->value;
        };
        //Gebe den Auswertungstext aus
        $markup = $this->erwartungcheckErgebnisAusgabe($ausgabeText, $percent, $userString, false);

        return ['#markup' => $markup];
    }

    /**
     * Diese Funktion erstellt das HTML-Markup für den Ergebnistext, der am Ende des Erwartungschecks
     * dargestellt wird. Der Parameter @param codeFlag dient dazu einen Zufallsstring mit auszugeben.
     *
     * @param array $ausgabeText
     * @param [type] $percent
     * @param [type] $userString
     * @param [type] $codeFlag
     * @return void
     */
    public function erwartungcheckErgebnisAusgabe(array $ausgabeText, $percent, $userString, $codeFlag) {
        //Wenn nur ein Feld angelegt wurde, wird der Wert hier ausgegeben.
        //Test
        if (count($ausgabeText) == 1) {
              $markup =  '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen';
              $markup .= 'von Studierenden und Lehrenden aus dem Fachbereich überein.</p>';
              if ($codeFlag) {
                  $markup .= '<p>Hier Ihr Code [' . $userString . ']</p>';
              }
              $markup .= $ausgabeText[0];

              return $markup;

              //Wenn zwei Felder angelegt wurden, soll je nach erreichter Prozentzahl das passende Feld ausgegeben werden.
          } else if (count($ausgabeText) == 2) {

              $bereich = 0;

              if ($percent > 50) {
                  $bereich = 1;
              }


                $markup =  '<p>Ihre Erwartungen an das Studium stimmen zu [' . $percent . '%] mit den Erwartungen';
                $markup .= 'von Studierenden und Lehrenden aus dem Fachbereich überein.</p>';
                if ($codeFlag) {
                    $markup .= '<p>Hier Ihr Code [' . $userString . ']</p>';
                }
                $markup .= $ausgabeText[$bereich];

              return $markup;

          //Analog fuer drei Felder
          } else if (count($ausgabeText) == 3) {

              $bereich = 0;

              if ($percent > 33 && $percent <= 66) {
                  $bereich = 1;
              } else if ($percent > 66) {
                  $bereich = 2;
              }


                $markup ='<p>Ihre Erwartungen an das Studium stimmen zu [' . $percent . '%] mit den Erwartungen';
                $markup .=  'von Studierenden und Lehrenden aus dem Fachbereich überein.</p>';
                if ($codeFlag) {
                    $markup .=  '<p>Hier Ihr Code [' . $userString . ']</p>';
                }
                $markup .= $ausgabeText[$bereich];

              return $markup;

        //Analog fuer vier Felder
          } else if (count($ausgabeText) == 4) {

            $bereich = 0;

            if ($percent > 25 && $percent <= 50) {
              $bereich = 1;
            }

            else if ($percent > 50 && $percent <= 75) {
              $bereich = 2;
            }

            else if ($percent > 75) {
              $bereich = 3;
            }



                $markup =  '<p>Ihre Erwartungen an das Studium stimmen zu [' . $percent . '%] mit den Erwartungen';
                $markup .= 'von Studierenden und Lehrenden aus dem Fachbereich überein.</p>';
                if ($codeFlag) {
                    $markup .= '<p>Hier Ihr Code [' . $userString . ']</p>';
                }
                $markup .= $ausgabeText[$bereich];
              return $markup;

            //Analog fuer fuenf Felder
          } else if (count($ausgabeText) == 5) {

            $bereich = 0;

            if ($percent > 20 && $percent <= 40) {
              $bereich = 1;
            }

            else if ($percent > 40 && $percent <= 60) {
              $bereich = 2;
            }

            else if ($percent > 60 && $percent <= 80) {
              $bereich = 3;
            }

            else if ($percent > 80) {
              $bereich = 4;
            }


              $markup =  '<p>Ihre Erwartungen an das Wirtschaftsinformatik-Studium stimmen zu [' . $percent . '%] mit den Erwartungen';
              $markup .= 'von Studierenden und Lehrenden aus dem Fachbereich überein und sind nicht sehr realistisch.</p>';
              if ($codeFlag) {
                  $markup .= '<p>Hier Ihr Code [' . $userString . ']</p>';
              }
              $markup .= $ausgabeText[$bereich];
            return $markup;

          } else {

              $markup = '<p>Falls nichts zutrifft</p>';
            return $markup;
          }
    }


    public function erwartungscheckQuiz($studiengang_nid) {
        // TODO remove function and corresponding routing
        //Pruefe welcher Studiengang
        //$studiengang = 32; // "Erwartungscheck Wirtschaftsinformatik"
        //Pruefe ob zum Studiengang ein Erwartungscheck existiert
        $nid = $studiengang_nid;
        $node_storage = \Drupal::entityManager()->getStorage('node');
        $node = $node_storage->load($nid);
        //Pruefe ob es sich um einen Studiengang Node handelt
        if (!$node->bundle() === 'studiengang') {
          \Drupal::messenger()->addMessage('Die URL weist keinen Verweis auf einen Studiengang auf.');
        }

        //dsm($node);
        foreach($node->field_erwartungscheck as $reference) {
          $erwartungschecks[] = $reference->target_id;
        }
        //Pruefe das es genau einen Erwartungscheck gibt
        if (count($erwartungschecks) == 1) {
          //Gebe Fragen in einer Form aus sende dazu die nid des referenzierten Erwartungschecks,
          //diese befindet sich in $erwartungchecks[0], da es nur einen Erwartungscheck pro Studiengang geben darf
          $form = \Drupal::formBuilder()->getForm('Drupal\erwartungscheck\Form\ErwartungscheckForm', $erwartungschecks[0]);
          return $form;
        //Falls es mehrere Erwartungschecks fuer einen Studiengang gibt, zeige eine Nachricht
        } else if (count($erwartungschecks > 1)) {
          return ['#markup' => '<p>Für diesen Studiengang existieren mehrere Erwartungschecks!'];
        }
        //Falls es keine Erwartungschecks gibt, zeige eine Nachricht
        return ['#markup' => '<p>Für diesen Studiengang wurde bisher kein Erwartungscheck angelegt!'];


    }

    public function zeigeCheck($check) {

        $nids = \Drupal::entityQuery('node')->condition('type','studiengang')->condition('title', $check)->execute();

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
