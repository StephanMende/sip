<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 24.12.18
 * Time: 18:52
 */

namespace Drupal\berufliche_vorbildung\Helper;


class Berufsgruppe
{

    public function getBerufsgruppen() {
        //get nids of content type berufsgruppe
        $nids = \Drupal::entityQuery('node')
            ->condition('type','berufsgruppe')->execute();

        //node object from the nid
        $nodes = node_load_multiple($nids);

        //get Berufsgruppe
        foreach ($nodes as $node) {
            $berufsgruppe[$node->getTitle()] = $node->getTitle();
        };

        return $berufsgruppe;
    }

    /**
     * This function returns alle Berufe in an array named options
     * based on Berufsgruppe
     * @param string $key
     * @return array options
     */

    public function getBerufe($key = '') {
        //$berufsgruppen = static::getBerufsgruppen();
        $berufsgruppen = $this->getBerufsgruppen();
        foreach($berufsgruppen as $berufsgruppe) {
            //kint($berufsgruppe);
            switch ($key) {
                case $berufsgruppe:
                    $options = $this->getBerufeBasedOnBerufsgruppe($berufsgruppe);
            }
        }


        return $options;
    }

    public function getBerufeBasedOnBerufsgruppe($berufsgruppe) {
        //get nid from title of berufsgruppe
        $nodes = \Drupal::entityTypeManager()
            ->getStorage('node')
            ->loadByProperties(['title' => $berufsgruppe]);
        foreach ($nodes as $node) {
            $nid = $node->id();
        }

        //get target ids for the nid
        $node = node_load($nid);
        $target_ids = $node->get("field_berufsgruppe_beruf")->getValue();
        foreach ($target_ids as $target_id) {
            $target_id = $target_id["target_id"];
            $node_beruf = node_load($target_id);
            $beruf_title = $node_beruf->getTitle();
            //write in array
            $berufe[$beruf_title] = $beruf_title;

        }

        return $berufe;



    }

}