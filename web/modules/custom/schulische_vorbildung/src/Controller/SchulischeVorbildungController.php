<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 12.01.19
 * Time: 23:48
 */

namespace Drupal\schulische_vorbildung\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\schulische_vorbildung\Helper\DatabaseHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SchulischeVorbildungController extends ControllerBase
{

    private $database_helper;

    public function __construct(DatabaseHelper $databaseHelper)
    {
        $this->database_helper = $databaseHelper;
    }

    public static function create(ContainerInterface $container)
    {
        $databaseHelper = $container->get('schulische_vorbildung.database');
        return new static ($databaseHelper);
    }




    public function showStudiengaenge($schulabschluss_nid) {
        $result = $this->database_helper->getStudiengangBySchulabschlussId($schulabschluss_nid);

        $config = $this->config('berufliche_vorbildung.settings');
        $text = $config->get('schulische_vorbildung_studiengang_text');

        if(!is_null($result)) {
            //collect all nids of studiengang content
            foreach ($result as $studiengang) {
                $studiengang_nids[] = $studiengang->entity_id;
            }
            //load the studiengang nodes
            $nodes = node_load_multiple($studiengang_nids);
            //ksm($nodes);
            //create content to show the Studiengaenge

            //$markup = $text;
            foreach ($nodes as $node) {
                //ksm($node->get('body')->getString());
                /**
                $studiengang_titles[] = [
                    'title' => $node->getTitle(),
                    'beschreibung' => check_markup($node->get('body')->value, $node->get('body')->format),
                ];
                 * */

                $studiengang_titles[] =  ['title' => $node->getTitle(), 'link' => $node->get('field_studiengang_links')->uri] ;
            }

            sort($studiengang_titles);

            $html = '<p>' . $text['value'] . '</p><ul>';
            foreach($studiengang_titles as $studiengang_title) {
              $html .= '<li><a target="_blank" href="' . $studiengang_title['link'] . '">' . $studiengang_title['title'] . '</a></li>';
            }

            $html .= '</ul>';

            return ['#markup' => $html];

        } else {

          return [
            '#type' => 'processed_text',
            '#text' => $config->get('berufliche_vorbildung_kein_studiengang.value'),
            '#format' => $config->get('berufliche_vorbildung_kein_studiengang.format')
            ];

        }
    }
}
