<?php

namespace Drupal\zsb_usability\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;

/**
 * Plugin implementation of the 'zsb_link' formatter.
 *
 * @FieldFormatter(
 *   id = "zsb_link",
 *   label = @Translation("ZSB Link"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class ZsbLink extends EntityReferenceLabelFormatter {
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {

    // let EntityReferenceLabelFormatter create the reference links
    $elements = parent::viewElements($items, $langcode);

    // add the zsb typo3 link above the links
    $fieldName = $items->getName();
    if (count($elements) > 0) {
      if ($fieldName === 'field_erwartungscheck') {
        $route = '/erwartungscheck/quiz/';
        $zsbLink = $this->generateLink($items, $route);
        array_unshift($elements, $zsbLink);
      }

      if ($fieldName === 'field_fachquiz') {
        $route = '/fachquiz/quiz/';
        $zsbLink = $this->generateLink($items, $route);
        array_unshift($elements, $zsbLink);
      }
    }

    return $elements;
  }

  /**
   * Generates a ZSB TYPO3 link.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *   The field values to be rendered.
   * @param string                                    $route
   *   Internal route path for 'Fachquiz' or 'Erwartungscheck'.
   *
   * @return array
   *   The render array of the generated copy link.
   */
  protected function generateLink(FieldItemListInterface $items, string $route): array {
    $host = \Drupal::request()->getSchemeAndHttpHost();
    $studiengangId = $items->getParent()->getEntity()->Id();
    $quizUrl = $host . $route . $studiengangId;

    $zsbLink = [
      '#theme' => 'clipboardjs',
      '#text'  => $quizUrl,
      '#alert_style'  => 'none',
      //'#alert_text'   => 'Copy was successful!',
      //'#button_label' => 'Click'
    ];

    return $zsbLink;
  }
}
