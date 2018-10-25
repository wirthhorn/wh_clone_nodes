<?php
namespace Drupal\wh_clone_nodes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\node\Entity\Node;
use Drupal\Component\Datetime;

/**
 * Cloning Nodes with Content
 *
 * @param ID        ID of a node that will be cloned.
 * 
 * @author          Armin Subasic <subasic@wirth-horn.de>
 * @package         Wirth und Horn
 * @version         1.0.0 (24.10.2018)
 * @throws          Null
 * @return          RedirectResponse
 */
class CloneController extends ControllerBase
{

    public function clone($id)
    {
        $node = Node::load($id);
        if ($node === null) {
            drupal_set_message(t('Node with id @id does not exist.', ['@id' => $id]), 'error');
        } else {

            $nodeDuplicate = $node->createDuplicate();

            // foreach ($nodeDuplicate->field_paragraphs as $field) {
            //     $field->entity = $field->entity->createDuplicate();
            // }
            $nodeDuplicate->title = $nodeDuplicate->getTitle() . " Clone";
            $nodeDuplicate->changed = \Drupal::time()->getCurrentTime();
            $nodeDuplicate->save();

            drupal_set_message(
            	t("Node has been created. <a href='/node/@id/edit' target='_blank'>Edit now</a>", [
                  '@id' => $nodeDuplicate->id(),
                  '@title' => $nodeDuplicate->getTitle()]
            	 ), 'status');
        }

        return new RedirectResponse('/admin/content');
    }
}