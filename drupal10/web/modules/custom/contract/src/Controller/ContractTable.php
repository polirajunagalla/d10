<?php

namespace Drupal\contract\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Controller.
 */
class ContractTable extends ControllerBase {

  /**
   * Controller table.
   */
  public function contract_table() {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'contract')
      ->accessCheck(FALSE);
    $nids = $query->execute();

    $nodes = Node::loadMultiple($nids);
    $contracts = [];
    foreach ($nodes as $node) {
      $contracts[] = [
        'title' => $node->getTitle(),
        'recipient_name' => $node->get('field_recipient_name')->value,
        'sender_name' => $node->get('field_sender_name')->value,
        'date' => $node->get('field_date')->value,
      ];
    }

    return [
    // Reference to the theme hook.
      '#theme' => 'contract_table',
    // Pass the data to the template.
      '#contracts' => $contracts,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}
