<?php

namespace Drupal\berufliche_vorbildung\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class BeruflicheVorbildungConfigForm extends ConfigFormBase {
    const SETTINGS = 'berufliche_vorbildung.settings';

    public function getFormId()
    {
        return 'berufliche_vorbildung_settings';
    }

    protected function getEditableConfigNames()
    {
        return [
            static::SETTINGS,
        ];
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config(static::SETTINGS);

        $form['berufliche_vorbildung_studiengang_text'] = [
            '#type' => 'text_format',
            '#title' => $this->t('Text für den Studiengang'),
            '#default_value' => $config->get('berufliche_vorbildung_studiengang_text.value'),
            '#format' => $config->get('berufliche_vorbildung_studiengang_text.format'),
        ];

        return parent::buildForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->configFactory->getEditable(static::SETTINGS)
            ->set('berufliche_vorbildung_studiengang_text', $form_state->getValue('berufliche_vorbildung_studiengang_text'))
            ->save();

        parent::submitForm($form, $form_state);
    }
}
