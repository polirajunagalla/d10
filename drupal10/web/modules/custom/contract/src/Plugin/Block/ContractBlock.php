<?php

namespace Drupal\contract\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a Contract List Block.
 *
 * @Block(
 *   id = "contract_list_block",
 *   admin_label = @Translation("Contract List Block")
 * )
 */
class ContractBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */

  /**
   * An array of contracts containing ields.
   */
  public function build() {
    $query = \Drupal::entityQuery('node')->condition('type', 'contract')->accessCheck(FALSE);
    $nids = $query->execute();
    $nodes = Node::loadMultiple($nids);
    $contracts = [];
    foreach ($nodes as $node) {
      $contracts[] = ['title' => $node->getTitle(), 'recipient_name' => $node->get('field_recipient_name')->value, 'sender_name' => $node->get('field_sender_name')->value, 'date' => $node->get('field_date')->value];
    }

    return [
    // Reference to the theme hook.
      '#theme' => 'contract_table',
    // Pass the data to the template.
      '#contracts' => $contracts,
      '#cache' => ['max-age' => 0],
    ];
  }

}
