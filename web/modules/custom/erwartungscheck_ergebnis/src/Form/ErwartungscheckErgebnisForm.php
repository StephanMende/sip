<?php

namespace Drupal\erwartungscheck_ergebnis\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ErwartungscheckErgebnisForm extends FormBase {


    public function buildForm(array $form, FormStateInterface $form_state, $arg1 = null) {

        //Hole den Namen des Studiengangs aus der URL
        $studiengang = $arg1;
        //Speichere den Studiengang im temporären Speicher für die Submit Funktion
        $tempstore = \Drupal::service('user.private_tempstore')->get('erwartungscheck_ergebnis');
        $tempstore->set('studiengang', $studiengang);

        //Hole dir die Anzahl der Felder, die die Form bereits hat
        $ergebnistext_fields_num = $form_state->get('ergebnistext_fields_num');

        //Sicherstellen, dass es mindestens ein Feld gibt
        if ($ergebnistext_fields_num === null) {
            $ergebnistext_field = $form_state->set('ergebnistext_fields_num', 1);
            $ergebnistext_fields_num = 1;
        }

        $form['description'] = [
            '#type' => 'item',
            '#title' => $this->t('Erwartungscheck Ergebnistexte für den Studiengang "' . $studiengang . '"'),
            '#description' => $this->t('Hier erstellen Sie die Auswertungstext für den Erwartungstext'),
        ];

        $form['#tree'] = true;

        $form['erwartungscheck_ergebnis'] = [
            '#type' => 'fieldset',
            //Set up the wrapper so that AJAX will be able to replace the fieldset
            '#prefix' => '<div id="ergebnis-anzeige-wrapper">',
            '#suffix' => '</div>',
        ];



        for ($i = 0; $i < $ergebnistext_fields_num; $i++ ) {
            $form['erwartungscheck_ergebnis'][$i]['maximum'] = [
                '#type' => 'number',
                '#title' => $this->t('Maximaler Prozentwert'),
                '#default_value' => 20,
            ];

            $form['erwartungscheck_ergebnis'][$i]['ergebnistext'] = [
                '#type' => 'text_format',
                '#title' => $this->t('Ergebnistext'),
                '#format' => 'full_html',
                '#description' => $this->t('Ergebnistext der angezeigt wird.'),
                '#default_value' => 'fff',
              ];
        }

        $form['erwartungscheck_ergebnis']['actions'] = [
            '#type' => 'action',
        ];



        $form['erwartungscheck_ergebnis']['actions']['add_item'] = [
            '#type' => 'submit',
            '#value' => $this->t('Add another item'),
            '#submit' => ['::erwartungscheck_ergebnis_add_item'],
            '#ajax' => [
                'callback' => '::erwartungscheck_ergebnis_ajax_callback',
                'wrapper' => 'ergebnis-anzeige-wrapper',
            ],
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];



        return $form;
    }

    public function getFormId() {
        return 'erwartungscheck_ergebnis_form';
    }

    public function erwartungscheck_ergebnis_ajax_callback(array &$form, FormStateInterface $form_state) {
        return $form['erwartungscheck_ergebnis'];
    }

    public function erwartungscheck_ergebnis_add_item(array &$form, FormStateInterface $form_state) {
        $ergebnistext_field = $form_state->get('ergebnistext_fields_num');
        $add_button = $ergebnistext_field + 1;
        $form_state->set('ergebnistext_fields_num', $add_button);
        $form_state->setRebuild();
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

    }



    public function submitForm(array &$form, FormStateInterface $form_state) {

        //Hole den Studiengang aus dem temporären Speicher
        $tempstore = \Drupal::service('user.private_tempstore')
                    ->get('erwartungscheck_ergebnis');
        $studiengang = $tempstore->get('studiengang');
        //Hole alle Werte aus dem Formular
        $values = $form_state->getValue(
            [
            'erwartungscheck_ergebnis'
            ]
        );

        //In die Datenbank schreiben
        $connection = \Drupal::database();
        foreach ($values as $value) {

            if (!empty($value['maximum']) && !empty($value['ergebnistext']['value'])) {
                $maximum = $value['maximum'];
                $ergebnistext = $value['ergebnistext']['value'];
                //dsm($ergebnistext);
                //dsm($maximum);
            }


            try {
                $result = $connection->insert('erwartungscheck_ergebnis')
                ->fields(
                    [
                            'studiengang' => $studiengang,
                            'maximum' => $maximum,
                            'ergebnistext' => $ergebnistext,
                        ]
                )
                ->execute();
            } catch (Exception $e) {
                \Drupal::messenger()->addMessage($e->getMessage());

            }



            //$form_state->setRedirect('erwartungscheck_ergebnis.ergebnis_nachricht');
        }




    }




}