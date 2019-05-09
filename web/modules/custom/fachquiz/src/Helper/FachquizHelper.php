<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 08.05.19
 * Time: 09:00
 */

namespace Drupal\fachquiz\Helper;


class FachquizHelper {

  public function getFachquiz() {
    //get nids of content type erwartungscheck content type
    $nids = \Drupal::entityQuery('node')
      ->condition('type','fachquiz')->execute();

    //node objects from the nids
    $nodes =  node_load_multiple($nids);

    foreach ($nodes as $node) {
      $aufgaben = $node->get('field_fachquiz_aufgaben')->referencedEntities();
      foreach ($aufgaben as $aufgabe) {
        //dsm($aufgabe->body->value);
        //dsm($aufgabe->field_aufgabe_erklaerung->value);
        //dsm($aufgabe->field_aufgabe_frage->value);
        $antworten = $this->createAntwortOptionen($aufgabe->get('field_aufgabe_antwortoptionen')->referencedEntities());
        //dsm($antworten);
        $aufgaben_data[] = [
          'aufgabe' => $aufgabe->body->value,
          'frage' => $aufgabe->field_aufgabe_frage->value,
          'antwortoptionen' => $antworten,
          'erklaerung' => $aufgabe->field_aufgabe_erklaerung->value,
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