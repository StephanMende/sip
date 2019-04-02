<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 26.03.19
 * Time: 20:29
 */

namespace Drupal\fachquiz\Data;


class FachquizData {
private  $data =
  [
    [
      'aufgabe' => '<p>Ein Handwerker erhält für die Herstellung eines Stuhls 4 Euro. Im Rahmen einer 38-Stunden Woche fertigt er 323 Stühle.</p>',
      'frage' => '<p>Wie hoch ist sein Stundenlohn?',
      'fragetyp' => 'single choice',
      'antwortoptionen' => [
        '8,50€/Stunde',
        '9,50€/Stunde',
        '34,00€/Stunde',
        '80,75€/Stunde',
      ],
      'erklaerung' => '<p>Zur Berechnung des Stundenlohns müssen die Anzahl der Stühle mit dem Stücklohn multipliziert und durch die Wochenstunden geteilt werden:</p>
                           <p>323 Stühle * 4€/Stuhl / 38 Stunden = 34€/Stunde</p>',
    ],
    [
      'aufgabe' => '<p>Ein Handwerker erhält für die Herstellung eines Stuhls 4 Euro. Im Rahmen einer 38-Stunden Woche fertigt er 323 Stühle.</p>',
      'frage' => '<p>Wie hoch ist sein Stundenlohn?',
      'fragetyp' => 'single choice',
      'antwortoptionen' => [
        '8,50€/Stunde',
        '9,50€/Stunde',
        '34,00€/Stunde',
        '80,75€/Stunde',
      ],
      'erklaerung' => '<p>Zur Berechnung des Stundenlohns müssen die Anzahl der Stühle mit dem Stücklohn multipliziert und durch die Wochenstunden geteilt werden:</p>
                           <p>323 Stühle * 4€/Stuhl / 38 Stunden = 34€/Stunde</p>',
    ],
  ];

  /**
   * @return array
   */
  public function getData() {
    return $this->data;
  }

  /**
   * @param array $data
   */
  public function setData($data) {
    $this->data = $data;
  }
}