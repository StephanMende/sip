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
use Drupal\Core\Url;
use Drupal\Core\Link;


class FachquizController extends ControllerBase {

  public function content() {

    //Data
    //$fachquizData = new FachquizData();

    //dsm($fachquizData->getData()[0]['aufgabe']);

    return ['#markup' => 'Fachquiz'];

  }

  public function fachquizQuiz($studiengang_nid) {
      //Pruefe welcher Studiengang
      //$studiengang = 32; // "Erwartungscheck Wirtschaftsinformatik"
      //Pruefe ob zum Studiengang ein Erwartungscheck existiert
      $nid = $studiengang_nid;
      $node_storage = \Drupal::entityManager()->getStorage('node');
      $node = $node_storage->load($nid);
      //Pruefe ob es sich um einen Studiengang Node handelt
      if (!$node->bundle() === 'studiengang') {
          \Drupal::messenger()->addMessage($this->t('Die URL weist keinen Verweis auf einen Studiengang auf.'));
      }

      foreach($node->field_fachquiz as $reference) {
        $fachquizzes[] = $reference->target_id;
      }

      // Auslesen ob nach Abschluss des Erwartungschecks ein Token ausgegeben werden soll oder nicht
      $codeFlag = $node->field_fachquiz_token->value;

      //Pruefe das es genau einen Erwartungscheck gibt
      if (count($fachquizzes) == 1) {
        //Gebe Fragen in einer Form aus sende dazu die nid des referenzierten Erwartungschecks,
        //diese befindet sich in $erwartungchecks[0], da es nur einen Erwartungscheck pro Studiengang geben darf
        $fachquiz_nid = $fachquizzes[0];
        $form = \Drupal::formBuilder()->getForm('Drupal\fachquiz\Form\FachquizForm', $fachquiz_nid);
        return $form;
      //Falls es mehrere Erwartungschecks fuer einen Studiengang gibt, erstelle eine Liste mit URLs die auf die
      //Fachquizzes zeigen
      } else if (count($fachquizzes > 1)) {
        foreach ($fachquizzes as $fachquiz_id) {
          $node = $node_storage->load($fachquiz_id);
          //dsm($node->title->value);
          $links[] = Link::fromTextAndUrl($node->title->value, Url::fromRoute('fachquiz.form', ['fachquiz_nid' => $fachquiz_id, 'codeFlag' => $codeFlag]));
        }
        //Erzeuge ungeordnete Liste
        $html_list = [
          '#theme' => 'item_list',
          '#list_type' => 'ul',
          '#title' => 'Fachquizzes',
          '#items' => $links,
        ];
        return $html_list;
      }
      //Falls es keine Erwartungschecks gibt, zeige eine Nachricht
      return ['#markup' => '<p>' . $this->t('Für diesen Studiengang wurde bisher kein Erwartungscheck angelegt!') . '</p>'];

  }

  public function fachquizInfo($fachquiz_nid, $percent) {
    //Hole den Prozentwert aus der URL
    $percent = $percent;
    $userString = "";
    $codeFlag = false; //TODO: Aendern, wenn der Config Import Fehler behoben wurde
    //Generiere den Random String
    $userData = new FachquizData();
    // Access Token nur generieren und ausgeben, wenn diese Option für den Studiengang aktiviert ist
    if ($codeFlag == TRUE) {
      $userString = $userData->randomString();
    }
    //Lese die Felder aus dem Fachquiz aus
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $node = $node_storage->load($fachquiz_nid);
    /**
    * Hier wird über das Feld field_ausgabeerwartungscheck itertiert und alle Wert aus diesem
    * im array $ausgabeText gespeichert.
    */
    foreach($node->field_fachquiz_auswertungstext as $item) {
        $ausgabeText[] = $item->value;
    }

    //dsm($ausgabeText);

    //$markup = ['#markup' => 'hhhh'];
    $markup = $this->fachquizErgebnisAusgabe($ausgabeText, $percent, $userString, $codeFlag);
    return ['#markup' => $markup];

  }
  public function auswertung($percent, $codeFlag) {

    $userData = new FachquizData();

    $markup = $this->t('Vielen Dank, dass Sie mitgemacht haben. Sie haben @percenct% erreicht. Ihre Auswertung wird hier auch erscheinen,
      wir entwickeln diese Komponente gerade...', array('percent' => $percent));

    // Access Token nur generieren und ausgeben, wenn diese Option für den Studiengang aktiviert ist
    if ($codeFlag == TRUE) {
      $userString = $userData->randomString();
      $markup .=  '<br>' . $this->t('Ihr Bewerbungscode: ') . $userString . ' ';
    }

    return['#markup' => $markup];
  }

  public function fachquizErgebnisAusgabe(array $ausgabeText, $percent, $userString, $codeFlag) {
    //Wenn nur ein Feld angelegt wurde, wird der Wert hier ausgegeben.
    //Test
    if (count($ausgabeText) == 1) {
          $markup =  '<p>' . $this->t('Sie haben [@percent%] bei diesem Fachquiz erreicht', array('percent' => $percent)) . '</p>';
          if ($codeFlag) {
              $markup .= '<p>' . $this->t('Hier Ihr Code [@userString]', array('userString' => $userString)) . '</p>';
          }
          $markup .= $ausgabeText[0];

          return $markup;

          //Wenn zwei Felder angelegt wurden, soll je nach erreichter Prozentzahl das passende Feld ausgegeben werden.
      } else if (count($ausgabeText) == 2) {

          $bereich = 0;

          if ($percent > 50) {
              $bereich = 1;
          }


            $markup =  '<p>' . $this->t('Sie haben [@percent%] bei diesem Fachquiz erreicht.', array('percent' => $percent)) . '</p>';
            if ($codeFlag) {
                $markup .= '<p>' . $this->t('Hier Ihr Code [@userString]', array('userString' => $userString)) . '</p>';
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


            $markup =  '<p>' $this->t('Sie haben [@percent%] bei diesem Fachquiz erreicht', array('percent' => $percent)) . '</p>';
            $markup .=  $this->t('von Studierenden und Lehrenden aus dem Fachbereich überein.') . '</p>';
            if ($codeFlag) {
                $markup .=  '<p>' . $this->t('Hier Ihr Code [@userString]', array('userString' => $userString)) .'</p>';
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



        $markup =  '<p>' . $this->t('Sie haben [@percent%] bei diesem Fachquiz erreicht', array('percent' => $percent)) . '</p>';
            if ($codeFlag) {
                $markup .= '<p>' . $this->t('Hier Ihr Code [@userString]', array('userString' => $userString)) . '</p>';
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


        $markup =  '<p>' . $this->t('Sie haben [@percent%] bei diesem Fachquiz erreicht', array('percent' => $percent)).  '</p>';
          if ($codeFlag) {
              $markup .= '<p>'. $this->t('Hier Ihr Code [@userString]', array('userString' => $userString)) . '</p>';
          }
          $markup .= $ausgabeText[$bereich];
        return $markup;

      } else {

          $markup = '<p>' . $this->t('Falls nichts zutrifft') . '</p>';
        return $markup;
      }
}

  public function helper($nid) {
    $fachzquizHelper = new FachquizHelper();
    $fachquiz_data = $fachzquizHelper->getFachquiz($nid);
    //dsm($fachquiz_data);
    return ['#markup' => 'Helper'];
  }

}
