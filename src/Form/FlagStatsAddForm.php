<?php

namespace Drupal\flag_stats\Form;

use Drupal\flag\Form\FlagAddForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the flag statistics form.
 *
 * @see Drupal\flag\Form\FlagAddForm
 */
class FlagStatsAddForm extends FlagAddForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $entity_type = NULL) {
    $flag = $this->entity;
    $form = parent::buildForm($form, $form_state);

    $form['flag_stats'] = [
      '#type' => 'details',
      '#open' => TRUE,
      '#title' => $this->t('Flag Statistics'),
      '#tree' => FALSE,
      '#weight' => 20,
    ];

    $form['flag_stats']['flag_stat'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Flag Statistics'),
      '#description' => $this->t('Add statistics entry after flaging entity.'),
    ];

    $form['flag_stats']['unflag_stats'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable UnFlag Statistics'),
      '#description' => $this->t('Add statistics entry after unflaging entity.'),
    ];

    $form['flag_stats']['delete_flag_stat'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Remove Flag Statistics entry for Unflagged entity'),
      '#description' => $this->t('Remove flagged statistics entry after unflaging entity.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $flag = $this->entity;
    // Save extra configurations.
    $flag->setThirdPartySetting('flag_stats', 'flag_stat', $form_state->getValue('flag_stat'));
    $flag->setThirdPartySetting('flag_stats', 'unflag_stats', $form_state->getValue('unflag_stats'));
    $flag->setThirdPartySetting('flag_stats', 'delete_flag_stat', $form_state->getValue('delete_flag_stat'));

    return parent::save($form, $form_state);
  }

}
