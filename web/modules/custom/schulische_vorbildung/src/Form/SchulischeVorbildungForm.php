<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 12.01.19
 * Time: 22:28
 */

namespace Drupal\schulische_vorbildung\Form;

use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\schulische_vorbildung\Classes\SchulischeVorbildung;


class SchulischeVorbildungForm extends FormBase
{
    private $schulische_vorbildung;

    public static function create(ContainerInterface $container)
    {
        return new static($container->get('schulabschluss'));
    }

    public function __construct(SchulischeVorbildung $schulischeVorbildung)
    {
        $this->schulische_vorbildung = $schulischeVorbildung;
    }

    public function getFormId()
    {
        return 'schulische_vorbildung_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $schulabschluesse = $this->schulische_vorbildung->getSchulabschluesse();

        $form['fieldset_schulabschluss'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Wählen Sie einen Schulabschluss'),
        ];

        $form['fieldset_schulabschluss']['schulabschluss_dropdown'] = [
            '#type' => 'select',
            '#title' => $this->t('Schulabschluss'),
            '#options' => $schulabschluesse,
        ];

        $form['fieldset_schulabschluss']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $schulabschluss_title = $form_state->getValue('schulabschluss_dropdown');
        $schulabschluss_nid = $this->schulische_vorbildung->getNidOfSchulabschluss($schulabschluss_title);

        $url = Url::fromRoute('schulische_vorbildung.controller')
            ->setRouteParameter('schulabschluss_nid', $schulabschluss_nid);

        $form_state->setRedirectUrl($url);
    }
}