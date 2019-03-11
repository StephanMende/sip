<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 29.11.18
 * Time: 12:04
 */

namespace Drupal\berufliche_vorbildung\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\berufliche_vorbildung\Helper\Berufsgruppe;
use Drupal\Core\Link;
use Drupal\Core\Entity;

class BeruflicheVorbildungForm extends FormBase

{
    private $berufsgruppen;

    public function __construct(Berufsgruppe $berufsgruppen)
    {
        $this->berufsgruppen = $berufsgruppen;
    }

    public static function create(ContainerInterface $container) {
        $berufsgruppen = $container->get('berufliche_vorbildung.berufsgruppen');
        return new static($berufsgruppen);
    }

    public function getFormId()
    {
        return 'berufliche_vorbildung';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        // TODO: Implement buildForm() method.

        $berufsgruppe = static::getBerufsgruppen();

        //$berufsgruppe = $this->berufsgruppen->getBerufsgruppen();

        //The options available in the second dropdown dependent on berufsgruppen

        //If form state does not contain a value for instruments family yet -- e.g.
        //when the form is first build -- we can use whatever is first
        if(empty($form_state->getValue('berufsgruppen_dropdown'))) {
            $selected_berufsgruppe = key($berufsgruppe);
        } else {
            //Get the value if it already exists
            $selected_berufsgruppe = $form_state->getValue('berufsgruppen_dropdown');
        }


        //create form with arrays
        $form['berufsgruppen_fieldset'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Wählen Sie eine Berufsgruppe'),
        ];

        $form['berufsgruppen_fieldset']['berufsgruppen_dropdown'] = [
          '#type' => 'select',
          '#title' => $this->t('Berufsgruppe'),
          '#options' => $berufsgruppe,
          '#default_value' => $selected_berufsgruppe,
          //Bind AJAX callback to event
          '#ajax' => [
              //Name of the method to call. This will be responible for returning
              //a response, and will be called after submitForm() when processing an
              //AJAX request
              'callback' => '::berufsgruppenDropdownCallback',
              'wrapper' => 'beruf-fieldset-container',
              'event' => 'change',
          ],
        ];

        $form['berufsgruppen_fieldset']['choose_berufsgruppe'] = [
          '#type' => 'submit',
          '#value' => $this->t('Wählen Sie.'),
            //This hides the button using the states system
          '#states' => [
              'visible' => ['body' => ['value' => TRUE]],
          ],
        ];

        $form['beruf_fieldset_container'] = [
          '#type' => 'container',
          '#attributes' => ['id' => 'beruf-fieldset-container'],
        ];

        $form['beruf_fieldset_container']['beruf_fieldset'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Wählen Sie einen Beruf.'),
        ];

        $form['beruf_fieldset_container']['beruf_fieldset']['beruf_dropdown'] = [
            '#type' => 'select',
            '#title' => $berufsgruppe[$selected_berufsgruppe] . ' ' . $this->t('Berufe'),
            '#options' => static::getBerufe($selected_berufsgruppe),
            //'#options' => $this->berufsgruppen->getBerufe($selected_berufsgruppe);
            '#default_value' => !empty($form_state->getValue('beruf_dropdown')),
        ];

        // This submit button triggers a normal non AjAX submission of the form
        $form['beruf_fieldset_container']['beruf_fieldset']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        if ($selected_berufsgruppe == '') {
            // Change the field title to provide user with some feedback on why the
            // field is disabled.
            $form['beruf_fieldset_container']['beruf_fieldset']['beruf_dropdown']['#title'] = $this->t('You must choose an instrument family first.');
            $form['beruf_fieldset_container']['beruf_fieldset']['beruf_dropdown']['#disabled'] = TRUE;
            $form['beruf_fieldset_container']['beruf_fieldset']['submit']['#disabled'] = TRUE;
        }

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        // TODO: Implement submitForm() method.

        $trigger = (string) $form_state->getTriggeringElement()['#value'];
        //kint($trigger);
        if($trigger == 'Absenden') {
            // Process submitted form data
            /*
            $this->messenger->addStatus($this->t('Your values have been submitted Berufsgruppe: @berufsgruppe, Beruf: @beruf', [
                '@berufsgruppe' => $form_state->getValue('berufsgruppen_dropdown'),
                '@beruf' => $form_state->getValue('beruf_dropdown'),
            ]));
            */



            //Hole nid des Berufs
            $nid = $this->_get_nid_of_beruf($form_state->getValue('beruf_dropdown'));

            //dsm($node);
            //Hole alle node die auf die nid des Berufs referenzieren
            $target_ids = $this->_getStudiengaenge();
            //ksm($target_ids);

            //gehe zu Link der die Studiengänge anzeigt
            /*
            $this->messenger()->addStatus($this->t('Your values have been submitted Berufsgruppe: @berufsgruppe, Beruf: @beruf, nid: @nid'  , [
                '@berufsgruppe' => $form_state->getValue('berufsgruppen_dropdown'),
                '@beruf' => $form_state->getValue('beruf_dropdown'),
                '@nid' => $nid,
            ]));
            */

            $beruf_id = $nid;

            $url = \Drupal\Core\Url::fromRoute('berufliche_vorbildung.controller')
                ->setRouteParameter('beruf_id', $beruf_id);
            $form_state->setRedirectUrl($url);



        } else {
            // Rebuild the form. This causes buildForm() to be called again before the
            // associated Ajax callback. Allowing the logic in buildForm() to execute
            // and update the $form array so that it reflects the current state of
            // the instrument family select list.
            $form_state->setRebuild();
        }
    }

    public function _get_nid_of_beruf($beruf) {
        $node = \Drupal::entityTypeManager()
            ->getStorage('node')
            ->loadByProperties(['title' => $beruf]);
        $node = reset($node);
        //dsm($node->id());
        return $node->id();

    }


    public static function _getStudiengaenge() {
        $nids = \Drupal::entityQuery('node')
            ->condition('type', 'studiengang')
            ->execute();

        $nodes = node_load_multiple($nids);

        foreach ($nodes as $node) {
            $target_ids[] = $node->get('field_studiengang_berufe');

        }

        return $target_ids;
    }

    /**
     * This function fetches all berufsgruppen from the database
     * Berufsgruppe is a Content Type
     * @return array berufsgruppe
     */

    public static function getBerufsgruppen() {
        //get nids of content type berufsgruppe
        $nids = \Drupal::entityQuery('node')
            ->condition('type','berufsgruppe')->execute();

        //node object from the nid
        $nodes = node_load_multiple($nids);

        //get Berufsgruppe
        foreach ($nodes as $node) {
           $berufsgruppe[$node->getTitle()] = $node->getTitle();
        };

        return $berufsgruppe;
    }


    /**
     * This function returns alle Berufe in an array named options
     * based on Berufsgruppe
     * @param string $key
     * @return array options
     */

    public static function getBerufe($key = '') {
        $berufsgruppen = static::getBerufsgruppen();
        //$berufsgruppen = $this->berufsgruppen->getBerufsgruppen();
        foreach($berufsgruppen as $berufsgruppe) {
            //kint($berufsgruppe);
            switch ($key) {
                case $berufsgruppe:
                    $options = static::getBerufeBasedOnBerufsgruppe($berufsgruppe);
            }
        }


        return $options;
    }




    public static function getBerufeBasedOnBerufsgruppe($berufsgruppe) {
        //get nid from title of berufsgruppe
        $nodes = \Drupal::entityTypeManager()
            ->getStorage('node')
            ->loadByProperties(['title' => $berufsgruppe]);
        foreach ($nodes as $node) {
            $nid = $node->id();
        }

        //get target ids for the nid
        $node = node_load($nid);
        $target_ids = $node->get("field_berufsgruppe_beruf")->getValue();
        foreach ($target_ids as $target_id) {
            $target_id = $target_id["target_id"];
            $node_beruf = node_load($target_id);
            $beruf_title = $node_beruf->getTitle();
            //write in array
            $berufe[$beruf_title] = $beruf_title;

        }

        return $berufe;



    }



    public function berufsgruppenDropdownCallback(array $form, FormStateInterface $form_state) {
        return $form['beruf_fieldset_container'];
    }


}