<?php

namespace Drupal\contract\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the Contract form.
 */
class ContractForm extends FormBase {

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * @param \Drupal\Core\Session\AccountInterface $account
   */
  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a CustomNodeForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(AccountInterface $account, EntityTypeManagerInterface $entity_type_manager) {
    $this->account = $account;
    $this->entityTypeManager = $entity_type_manager;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
      // Load the service required to construct this class.
      $container->get('current_user'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contract_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['document_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Document Title'),
      '#required' => TRUE,
    ];
    $form['recipient_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Recipient Name'),
      '#required' => TRUE,
    ];
    $form['sender_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Sender Name'),
      '#required' => TRUE,
    ];
    $form['date'] = [
      '#type' => 'date',
      '#title' => $this->t('Date'),
      '#required' => TRUE,
    ];
    $form['document_file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Document File'),
      '#upload_location' => 'public://documents/',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $node = Node::create([
      'type' => 'contract',
      'title' => $form_state->getValue('document_title'),
      'field_document_title' => $form_state->getValue('document_title'),
      'field_recipient_name' => $form_state->getValue('recipient_name'),
      'field_sender_name' => $form_state->getValue('sender_name'),
      'field_date' => $form_state->getValue('date'),
      'field_document_file' => $form_state->getValue('document_file'),
    ]);
    $node->save();

    $this->messenger()->addMessage($this->t('Contract created successfully.'));
    $form_state->setRedirect('<front>');
  }

}
