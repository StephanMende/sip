<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 06.05.19
 * Time: 19:48
 */

namespace Drupal\erwartungscheck\Helper;

use Drupal\Core\Language\LanguageInterface;
use Drupal\taxonomy\Entity\Term;

class ErwartungscheckHelper {

  public function getErwartungcheck($targetId) {
    //get nids of content type erwartungscheck content type
    $nids = \Drupal::entityQuery('node')
      ->condition('type','erwartungscheck')->condition('nid',$targetId)->execute();

    //node objects from the nids
    $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);

    //create aussagen based on content
    foreach($nodes as $node) {
      $aussagen = $node->get('field_erwartungscheck_aussagen')->referencedEntities();
      foreach($aussagen as $aussage) {


        $valueAussage = $aussage->body->view();
        $valueRueckmeldung = $aussage->field_rueckmeldung->view();
        $aussagen_data[] = [
          'aussage' => \Drupal::service('renderer')->render($valueAussage),
          'rueckmeldung' => \Drupal::service('renderer')->render($valueRueckmeldung),
          'richtige_antwort' => $this->getTextOfTid($aussage->field_richtige_antwort->target_id),
          'gruppe' => $this->getTextOfTid($aussage->field_gruppe->target_id),
        ];
      };
    };
    return $aussagen_data;
  }

  public function getTextOfTid($tid) {
    $term = Term::load($tid);
    // get language from route and translated term
    $currentLanguage = \Drupal::languageManager()->getCurrentLanguage(LanguageInterface::TYPE_CONTENT)->getId();
    $termTranslated = \Drupal::service('entity.repository')->getTranslationFromContext($term, $currentLanguage);

    $name = $termTranslated->getName();

    return $name;
  }
}
