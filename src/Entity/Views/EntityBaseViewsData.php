<?php

/**
 * @file
 * Contains \Drupal\content_entity_base\Entity\Views\EntityBaseViewsData.
 */

namespace Drupal\content_entity_base\Entity\Views;

use Drupal\Core\Entity\ContentEntityTypeInterface;
use Drupal\views\EntityViewsData;

/**
 * Provides the views data for a custom entity type.
 */
class EntityBaseViewsData extends EntityViewsData {

  /**
   * @param ContentEntityTypeInterface $entity
   * @return array
   */
  public function getViewsData(ContentEntityTypeInterface $entity = NULL) {


    $data = parent::getViewsData();

    if ($entity) {
      // Get some info to work off.
      /** @var string $entity_table */
      $entity_table = $entity->get('base_table');
      /** @var string $entity_data_table */
      $entity_data_table = $entity->get('data_table');
      /** @var string $entity_revision_table */
      $entity_revision_table = $entity->get('revision_table');
      // Replacements for all strings.
      $replacements = [
        '@entity_label' => $entity->getLabel(),
        '!entity_id' => $entity->id(),
      ];

      if (!empty($entity_data_table)) {
        // Add the entity ID field.
        $data[$entity_data_table]['id']['field']['id'] = 'field';
        // Add entity info.
        $data[$entity_data_table]['info']['field']['id'] = 'field';
        $data[$entity_data_table]['info']['field']['link_to_entity default'] = TRUE;
        // Add the bundle (type).
        $data[$entity_data_table]['type']['field']['id'] = 'field';

      }
      if (!empty($entity_table) && !empty($entity_revision_table)) {

          // Advertise this table as a possible base table.
        $data[$entity_revision_table]['table']['base']['help'] = $this->t('@entity_label revision is a history of changes to a "!entity_id" entity.', $replacements);
        $data[$entity_revision_table]['table']['base']['defaults']['title'] = 'info';

        // @todo EntityViewsData should add these relationships by default.
        //   https://www.drupal.org/node/2410275
        $data[$entity_revision_table]['id']['relationship']['id'] = 'standard';
        $data[$entity_revision_table]['id']['relationship']['base'] = $entity_table;
        $data[$entity_revision_table]['id']['relationship']['base field'] = 'id';
        $data[$entity_revision_table]['id']['relationship']['title'] = $this->t('@entity_label', $replacements);
        $data[$entity_revision_table]['id']['relationship']['label'] = $this->t('Get the actual @entity_label from a @entity_label revision.', $replacements);

        $data[$entity_revision_table]['revision_id']['relationship']['id'] = 'standard';
        $data[$entity_revision_table]['revision_id']['relationship']['base'] = $entity_table;
        $data[$entity_revision_table]['revision_id']['relationship']['base field'] = 'revision_id';
        $data[$entity_revision_table]['revision_id']['relationship']['title'] = $this->t('@entity_label', $replacements);
        $data[$entity_revision_table]['revision_id']['relationship']['label'] = $this->t('Get the actual @entity_label from a @entity_label revision.', $replacements);
      }
    }

    return $data;
  }
}
