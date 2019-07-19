<?php

namespace Drupal\drupal_moodle_integration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class MoodleSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'moodle_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'moodle.settings',
    ];
  }

    /**
     * @param array $form
     * @param FormStateInterface $form_state
     * @return array
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $config = $this->config('moodle.settings');

        $form['moodle'] = [
            '#title' => 'Moodle settings',
            '#type' => 'details',
            '#open' => true,
        ];

        $form['url'] = [
            '#type' => 'textfield',
            '#title' => 'Moodle Url',
            '#default_value' => $config->get('url'),
            '#description' => $this->t('Moodle Url'),
        ];

        $form['wstoken'] = [
            '#type' => 'textfield',
            '#title' => 'Moodle Token',
            '#default_value' => $config->get('wstoken'),
            '#description' => $this->t('Moodle Token'),
        ];

        $form['moodlewsrestformat'] = [
            '#type' => 'textfield',
            '#title' => 'Moodle rest format',
            '#default_value' => $config->get('moodlewsrestformat'),
            '#description' => $this->t('Moodle rest format'),
        ];
        return parent::buildForm($form, $form_state);
    }

    /**
     * @param array $form
     * @param FormStateInterface $form_state
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Retrieve the configuration.
        $values = $form_state->getValues();
        $config = $this->config('moodle.settings');
        $config->set('url', $values['url']);
        $config->set('wstoken', $values['wstoken']);
        $config->set('moodlewsrestformat', $values['moodlewsrestformat']);
        $config->save();
        parent::submitForm($form, $form_state);
    }

}
