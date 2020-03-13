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
    const SETTINGS = 'berufliche_vorbildung.settings';

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
            $text = '<p>Folgenden Studiengang können wir Ihnen auf Grund Ihrer beruflichen Vorbildung empfehlen:<br><strong>Hinweis:</strong>Dies ist nur eine Empfehlung, ob Sie letztendlich zugelassen werden entscheidet die Universität.</p>';
            $config = $this->config(static::SETTINGS);
            $text = $config->get('berufliche_vorbildung_studiengang_text');
            foreach ($nodes as $node) {
                //ksm($node->get('body')->getString());
                /**
                $studiengang_titles[] = [
                    'title' => $node->getTitle(),
                    'beschreibung' => check_markup($node->get('body')->value, $node->get('body')->format),
                ];
                **/
                $studiengang_titles[] =  ['title' => $node->getTitle(), 'link' => $node->get('field_studiengang_links')->uri] ;
            }

            sort($studiengang_titles);

            $html = '<p>' . $text . '</p><ul>';
            foreach($studiengang_titles as $studiengang_title) {
                $html .= '<li><a target="_blank" href="' . $studiengang_title['link'] . '">' . $studiengang_title['title'] . '</a></li>';
            }

            $html .= '</ul>';

            /*
            $item_list = [
                '#theme' => 'item_list',
                '#items' => $studiengang_titles,
                '#attributes' => ['class' => 'studiengaenge_berufliche_vorbildung'],
            ];

            $html .= \Drupal::service('renderer')->render($item_list);

            */

            return ['#markup' => $html];
            /**
            return ['#theme' => 'show_studiengaenge',
                '#studiengang_name' => $this->t('Wirtschaftsinformatik'),
                '#studiengaenge' => $studiengang_titles,
                '#title' => 'Empfohlene Studiengänge',
            ];
             * */
        } else {
            return [
                '#type' => 'markup',
                '#markup' => $this->t('Leider gibt es keine Studiengänge.')
            ];
        }
    }



}
