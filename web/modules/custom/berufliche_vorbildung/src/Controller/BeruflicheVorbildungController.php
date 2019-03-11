<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 10.12.18
 * Time: 19:56
 */
namespace Drupal\berufliche_vorbildung\Controller;
use Drupal\berufliche_vorbildung\Helper\DatabaseHelper;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;

class BeruflicheVorbildungController extends ControllerBase
{

    public function showStudiengaenge($beruf_id) {

        $studiengaenge = ['Wirtschaftsinformatik', 'Angewandte Informatik'];

        $databaseHelper = new DatabaseHelper();

        $result = $databaseHelper->getStudiengangByBerufId($beruf_id);

        if (!is_null($result)) {
            //collect all nids of studiengang content
            foreach ($result as $studiengang) {
                $studiengang_nids[] = $studiengang->entity_id;
            }
            //load the studiengang nodes
            $nodes = node_load_multiple($studiengang_nids);
            //create content to show the Studiengaenge
            foreach ($nodes as $node) {
                //ksm($node->get('body')->getString());
                $studiengang_titles[] = [
                    'title' => $node->getTitle(),
                    'beschreibung' => check_markup($node->get('body')->value, $node->get('body')->format),
                ];

            }


            return ['#theme' => 'show_studiengaenge',
                '#studiengang_name' => $this->t('Wirtschaftsinformatik'),
                '#studiengaenge' => $studiengang_titles,
                '#title' => 'Empfohlene Studiengänge',
            ];
        } else {
            return [
                '#type' => 'markup',
                '#markup' => $this->t('Leider gibt es keine Studiengänge.')
            ];
        }
    }



}