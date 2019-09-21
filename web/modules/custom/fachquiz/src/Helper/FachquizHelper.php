<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 08.05.19
 * Time: 09:00
 */

namespace Drupal\fachquiz\Helper;


class FachquizHelper {

  public function getFachquiz($fachquiz_nid) {
    //Hole das Fachquiz ueber die nid
    $nids = \Drupal::entityQuery('node')
      ->condition('type','fachquiz')->condition('nid', $fachquiz_nid)->execute();

    //node objects from the nids
    $nodes =  node_load_multiple($nids);

    foreach ($nodes as $node) {
      $aufgaben = $node->get('field_fachquiz_aufgaben')->referencedEntities();
      foreach ($aufgaben as $aufgabe) {
        $antworten = $this->createAntwortOptionen($aufgabe->get('field_aufgabe_antwortoptionen')->referencedEntities());
        $aufgaben_data[] = [
          'aufgabe' => $aufgabe->body->value,
          'frage' => $aufgabe->field_aufgabe_frage->value,
          'antwortoptionen' => $antworten,
          'erklaerung' => drupal_render($aufgabe->field_aufgabe_erklaerung->view()),
        ];
      }
    }

    return $aufgaben_data;
  }

  public function createAntwortOptionen($antwortoptionen) {
    foreach ($antwortoptionen as $antwortoption) {
      $antwort[] = $antwortoption->getTitle();
    }
    return $antwort;
  }
}
