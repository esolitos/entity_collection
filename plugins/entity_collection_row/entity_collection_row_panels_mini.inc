<?php
/**
 * @file
 * Plugin to render rows with mini panels.
 */

/**
 * Use a mini panel to render a row.
 */
class EntityCollectionRowPanelsMini extends EntityCollectionRow {

  /**
   * @see EntityCollectionRowInterface::getOptions()
   */
  public function getOptions($entity_type) {
    $panels = array();
    if (isset($this->settings['allowed_panels'])) {
      foreach ($this->settings['allowed_panels'] as $name => $enabled) {
        if ($enabled) {
          $panel = panels_mini_load($name);
          foreach ($panel->requiredcontexts as $context) {
            if ($context['name'] == 'entity:' . $entity_type) {
              $panels[$name] = $panel->admin_title;
              break;
            }
          }
        }
      }
    }
    return $panels;
  }
  /**
   * Get all available contexts.
   */
  protected function getContexts($item) {
    ctools_include('context');
    $available_contexts = array();
    $available_contexts[] = ctools_context_create('entity:' . $item->type, $item->content);
    if (isset($this->entity_collection)) {
      $available_contexts[] = ctools_context_create('entity:entity_collection', $this->entity_collection);
      if (isset($this->entity_collection->contexts)) {
        foreach ($this->entity_collection->contexts as $context) {
          $available_contexts[] = $context;
        }
      }
    }
    return $available_contexts;
  }

  /**
   * Get all available options.
   * @param arry $entity_types
   */
  public function getAllOptions($entity_types) {
    if (!is_array($entity_types)) {
      $entity_types = array($entity_types);
    }
    $mini_panels = panels_mini_load_all();
    $available_contexts = array();
    if (isset($this->entity_collection)) {
      $available_contexts[] = 'entity:entity_collection';
    }
    foreach ($entity_types as $type) {
      $available_contexts[] = 'entity:' . $type;
    }
    foreach ($this->entity_collection->requiredcontexts as $context) {
      $available_contexts[] = $context['name'];
    }
    $options = array();

    foreach ($mini_panels as $name => $mini_panel) {
      if (count($mini_panel->requiredcontexts) >= 1) {
        $applicable = TRUE;
        foreach ($mini_panel->requiredcontexts as $requiredcontext) {
          if (!in_array($requiredcontext['name'], $available_contexts)) {
            $applicable = FALSE;
            break;
          }
        }
        $options[$name] = $mini_panel->admin_title;
      }
    }
    return $options;
  }

  /**
   * @see EntityCollectionRowInterface::build()
   */
  public function build(EntityCollection $collection, EntityCollectionTreeNode $item, $langcode = NULL) {
    if (!isset($panels[$item->style])) {
      $panels[$item->style] = panels_mini_load($item->style);
    }
    $panel = $panels[$item->style];
    if (isset($panel)) {
      $context = ctools_context_match_required_contexts($panel->requiredcontexts, $this->getContexts($item));
      $panel->context = $panel->display->context = ctools_context_load_contexts($panel, FALSE, $context);
      $panel->display->owner = $panel;
      $panel->display->owner->id = $panel->name;
      return array(
        '#markup' => panels_render_display($panel->display),
      );
    }
    return array();
  }

  /**
   * Provide a settings form form for setting which mini panels are allowed.
   */
  public function settingsForm(&$form) {
    $types = array();
    foreach ($this->entity_collection->settings['allowed_bundles'] as $type => $bundles) {
      if (count($bundles)) {
        $types[] = $type;
      }
    }
    $form['allowed_panels'] = array(
      '#type' => 'checkboxes',
      '#options' => $this->getAllOptions($types),
      '#default_value' => isset($this->settings['allowed_panels']) ? $this->settings['allowed_panels'] : array(),
      '#title' => t('Allowed mini panels'),
    );
  }
}