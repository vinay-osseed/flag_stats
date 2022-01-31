<?php

/**
 * @file
 * Contains \Drupal\flag_stats\Stats\FlagStatsCount.
 */
namespace Drupal\flag_stats\Stats;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\flag\Event\FlagEvents;
use Drupal\flag\Event\FlaggingEvent;
use Drupal\flag\Event\UnflaggingEvent;

class FlagStatsCount implements EventSubscriberInterface {
  // Call database entry on flaging of entity.
  public function onFlag(FlaggingEvent $event) {
    $flagging = $event->getFlagging();
    $entity_nid = $flagging->getFlaggable()->id();
    $enity_type = $flagging->getFlaggable()->getEntityType()->id();
    $user_id = $flagging->getFlaggable()->getCurrentUserId();
    $flag = $flagging->getFlag();
    // Add flag statestics entry on entity flagged.
    if ($flag->getThirdPartySetting('flag_stats', 'flag_stat', NULL) == 1) {
      $query = \Drupal::database()->insert('flag_stats');
      $query->fields([
        'status' => 1,
        'flag_type' => $flag->getOriginalId(),
        'entity_id' => $entity_nid,
        'entity_type' => $enity_type,
        'uid' => $user_id[0],
        'timestamp' => REQUEST_TIME,
      ])->execute();
    }
  }

  // Call database entry on unflaging of entity.
  public function onUnflag(UnflaggingEvent $event) {
    $flagging = $event->getFlaggings();
    $flagging = reset($flagging);
    $entity_nid = $flagging->getFlaggable()->id();
    $enity_type = $flagging->getFlaggable()->getEntityType()->id();
    $user_id = $flagging->getFlaggable()->getCurrentUserId();
    $flag = $flagging->getFlag();

    // Remove flag statestics entry on entity unflagged if option is enabled
    // in configuation.
    if ($flag->getThirdPartySetting('flag_stats', 'delete_flag_stat', NULL) == 1) {
      $all_fids = db_select('flag_stats', 'f')
        ->fields('f', array('fid'))
        ->condition('f.entity_id', $entity_nid, '=')
        ->condition('f.uid', $user_id[0], '=')
        ->execute()
        ->fetchAll();

      foreach ($all_fids as $fid) {
        db_delete('flag_stats')
          ->condition('fid', $fid->fid, '=')
          ->execute();
      }
    }
    elseif ($flag->getThirdPartySetting('flag_stats', 'unflag_stats', NULL) == 1) {
      // Add flag statestics entry on entity unflagged.
      db_insert('flag_stats')
        ->fields(array(
          'status' => 0,
          'flag_type' => $flag->getOriginalId(),
          'entity_id' => $entity_nid,
          'entity_type' => $enity_type,
          'uid' => $user_id[0],
          'timestamp' => REQUEST_TIME,
        ))->execute();
    }
  }

  public static function getSubscribedEvents() {
    $events = [];
    $events[FlagEvents::ENTITY_FLAGGED][] = ['onFlag'];
    $events[FlagEvents::ENTITY_UNFLAGGED][] = ['onUnflag'];
    return $events;
  }

}
