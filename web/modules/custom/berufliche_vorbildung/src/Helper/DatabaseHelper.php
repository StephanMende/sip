<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 09.01.19
 * Time: 15:59
 */

namespace Drupal\berufliche_vorbildung\Helper;


class DatabaseHelper
{
    /**
     * This functions takes a nid in this case the nid from the content type
     * beruf and returns all target_ids (studiengang) from the beruf content
     * @param $beruf_id
     * @return array
     */
    public function getStudiengangByBerufId($beruf_id) {
        $connection = \Drupal::database();
        $sql = "SELECT * FROM node__field_studiengang_berufe WHERE field_studiengang_berufe_target_id = :beruf_id ";
        $result = $connection->query($sql, [':beruf_id' => $beruf_id]);


        foreach ($result as $row) {
            $studiengang[] = $row;
        }

        return $studiengang;
    }
}