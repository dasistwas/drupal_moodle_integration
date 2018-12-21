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
    public function getFormId()
    {
        return 'moodle_admin_settings';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return [
        'moodle.settings',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('moodle.settings');

        $form['moodle'] = [
        '#title' => 'Moodle settings',
        '#type' => 'details',
        '#open' => true,
        ];

        $form['moodle']['url'] = [
        '#type' => 'textfield',
        '#title' => 'Moodle Url',
        '#default_value' => $this->config('moodle.settings')->get('url'),
        '#description' => $this->t('Moodle Url'),
        ];

        $form['moodle']['wstoken'] = [
        '#type' => 'textfield',
        '#title' => 'Moodle Token',
        '#default_value' => $this->config('moodle.settings')->get('wstoken'),
        '#description' => $this->t('Moodle Token'),
        ];
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        // Retrieve the configuration.
        $this->configFactory->getEditable('moodle.settings')->set('url', $form_state->getValue('url'))->set('wstoken', $form_state->getValue('wstoken'))->save();
        parent::submitForm($form, $form_state);
    }
}
