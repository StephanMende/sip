<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 13.01.19
 * Time: 18:40
 */

namespace Drupal\schulische_vorbildung\Helper;


class DatabaseHelper
{

    public function getStudiengangBySchulabschlussId($schulabschluss_nid) {
        $connection = \Drupal::database();
        $sql = "SELECT * FROM node__field_studiengang_schulabschluss 
                WHERE field_studiengang_schulabschluss_target_id = :schulabschluss_nid;";
        $result = $connection->query($sql, ['schulabschluss_nid' => $schulabschluss_nid]);

        foreach ($result as $row) {
            $studiengang[] = $row;
        }

        return $studiengang;
    }
}