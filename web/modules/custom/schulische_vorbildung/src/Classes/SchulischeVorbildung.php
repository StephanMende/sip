<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 12.01.19
 * Time: 22:47
 */

namespace Drupal\schulische_vorbildung\Classes;


class SchulischeVorbildung
{

    public function getSchulabschluesse() {
        $nids = \Drupal::entityQuery('node')
            ->condition('type', 'schulabschluss')
            ->execute();

        $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);

        foreach ($nodes as $node) {
            $schulabschluesse[$node->getTitle()] = $node->getTitle();
        }

        return $schulabschluesse;

    }

    public function getNidOfSchulabschluss($schulabschluss_title) {
        $node = \Drupal::entityTypeManager()
            ->getStorage('node')
            ->loadByProperties(['title' => $schulabschluss_title]);
        $node = reset($node);
        return $node->id();
    }


}
