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
        //ksm($result);

        $text = '<p>Folgenden Studiengang können wir Ihnen auf Grund Ihrer schulischen Vorbildung empfehlen:<br/><strong>Hinweis:</strong>Dies
                              ist nur eine Empfehlung. Ob Sie letztlich zugelassen werden entscheidet die Universität.</p>';
        $studiengang_titles[] = [
          '#markup' => $text,
        ];

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

              $studiengang_titles[] = [
                 '#markup' => '<p><a href="'. $node->field_studiengang_links->uri .'">' .  $node->getTitle() . '</a></p>',
              ];
            }
            /**
            return ['#theme' => 'show_studiengaenge',
                '#studiengang_name' => $this->t('Wirtschaftsinformatik'),
                '#studiengaenge' => $studiengang_titles,
                '#title' => 'Empfohlene Studiengänge',
            ];
             * */
            //dsm($studiengang_titles);
            //$markup = $studiengang_titles;
            return $studiengang_titles;

        } else {

            return [
                    '#type' => 'markup',
                    '#markup' => $this->t('Leider gibt es keine Studiengänge.')
                    ];
        }
    }
}
