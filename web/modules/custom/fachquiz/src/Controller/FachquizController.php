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
          \Drupal::messenger()->addMessage('Die URL weist keinen Verweis auf einen Studiengang auf.');
      }

      foreach($node->field_fachquiz as $reference) {
        $fachquizzes[] = $reference->target_id;
      }
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
          dsm($node->title->value);
          $links[] = Link::fromTextAndUrl($node->title->value, Url::fromRoute('fachquiz.form', ['fachquiz_nid' => $fachquiz_id]));
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
      return ['#markup' => '<p>Für diesen Studiengang wurde bisher kein Erwartungscheck angelegt!'];

  }
  public function auswertung($percent) {

    $userData = new FachquizData();
    $userString = $userData->randomString();

    return['#markup' => 'Vielen Dank, dass Sie mitgemacht haben. Sie haben ' . $percent .'% erreicht. Ihre Auswertung wird hier auch erscheinen,
      wir entwickeln diese Komponente gerade... Ihr Bewerbungscode: ' . $userString . ' '];
  }

  public function helper($nid) {
    $fachzquizHelper = new FachquizHelper();
    $fachquiz_data = $fachzquizHelper->getFachquiz($nid);
    dsm($fachquiz_data);
    return ['#markup' => 'Helper'];
  }

}