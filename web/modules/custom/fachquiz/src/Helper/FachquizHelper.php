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
    $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);

    foreach ($nodes as $node) {
      $aufgaben = $node->get('field_fachquiz_aufgaben')->referencedEntities();
      foreach ($aufgaben as $aufgabe) {
        $antworten = $this->createAntwortOptionen($aufgabe->get('field_aufgabe_antwortoptionen')->referencedEntities());
        $valueErklaerungFalsch = ($aufgabe->field_aufgabe_erklaerung->view(['label' => 'hidden',]));
        $valueErklaerungRichtig = ($aufgabe->field_aufgabe_erklaerung_richtig->view(['label' => 'hidden',]));
        $aufgaben_data[] = [
          'aufgabe' => $aufgabe->body->value,
          'frage' => $aufgabe->field_aufgabe_frage->value,
          'antwortoptionen' => $antworten,
          'erklaerungFalsch' =>  \Drupal::service('renderer')->render($valueErklaerungFalsch),
          'erklaerungRichtig' =>  \Drupal::service('renderer')->render($valueErklaerungRichtig),
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
