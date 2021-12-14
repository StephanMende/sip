<?php

namespace Drupal\erwartungscheck_ergebnis\Controller;

use Drupal\Core\Controller\ControllerBase;

class ErwartungscheckErgebnisController extends ControllerBase {

    public function content($studiengang, $prozentzahl) {

        /**
        $maximum = 20;
        $connection = \Drupal::database();
        $query = $connection->select('erwartungscheck_ergebnis', 'e');
        $query->condition('e.maximum', $maximum, '<')->fields('ergebnistext');

        $result = $query->execute();
        */

        $message = $this->t('Sie haben für den Studiengang <strong>@studiengang</strong> folgenden Wert @prozentzahl erreicht',
                            array('studiengang' => $studiengang, 'prozentzahl' => $prozentzahl ));
        //$html = '<p>' . $this->t('Sie haben für den Studiengang') .'<em>' . $studiengang . '</em> folgenden Wert: ' . $prozentzahl . '% erreicht.';
        $html = '<p>' . $message . '</p>';
        return ['#markup' => $html];
    }

    public function nachricht() {

        $maximum = 20;
        $connection = \Drupal::database();
        $query = $connection->select('erwartungscheck_ergebnis', 'e');
        $query->condition('e.maximum', $maximum, '<=')->fields('e', ['ergebnistext']);
        $result = $query->execute();
        foreach ($result as $row) {
            // dsm($row);
        }
        $html = '<p>'.  $this->t('Vielen Dank für Ihre Zeit.') . '</p>';

        return ['#markup' => $html];
    }


}
