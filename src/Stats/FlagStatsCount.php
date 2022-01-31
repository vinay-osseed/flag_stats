<?php

namespace Drupal\flag_stats\Stats;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\flag\Event\FlagEvents;
use Drupal\flag\Event\FlaggingEvent;
use Drupal\flag\Event\UnflaggingEvent;
use Drupal\Core\Database\Connection;
use Drupal\Component\Datetime\TimeInterface;

/**
 * FlagStatsCount for manage flag/unflag events.
 */
class FlagStatsCount implements EventSubscriberInterface {

  /**
   * Declare database variable.
   *
   * @var \Drupal\Core\Database\Connection
   *   The Database Connection.
   */
  protected $connection;

  /**
   * Declare time variable.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Constructs new FlagStatsCount object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The Database Connection.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(Connection $connection, TimeInterface $time) {
    $this->connection = $connection;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database'),
      $container->get('datetime.time')
    );
  }

  /**
   * Call database entry on flaging of entity.
   */
  public function onFlag(FlaggingEvent $event) {
    $flagging = $event->getFlagging();
    $entity_nid = $flagging->getFlaggable()->id();
    $enity_type = $flagging->getFlaggable()->getEntityType()->id();
    $user_id = $flagging->getFlaggable()->getCurrentUserId();
    $flag = $flagging->getFlag();
    // Add flag statestics entry on entity flagged.
    if ($flag->getThirdPartySetting('flag_stats', 'flag_stat', NULL) == 1) {
      // Change from db_insert()
      $query = $this->connection->insert('flag_stats');
      $query->fields([
        'status' => 1,
        'flag_type' => $flag->getOriginalId(),
        'entity_id' => $entity_nid,
        'entity_type' => $enity_type,
        'uid' => $user_id[0],
      // Changes from REQUEST_TIME.
        'timestamp' => $this->time->getCurrentTime(),
      ])->execute();
    }
  }

  /**
   * Call database entry on unflaging of entity.
   */
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
      // Change from db_select()
      $all_fids = $this->connection->select('flag_stats', 'f')
        ->fields('f', ['fid'])
        ->condition('f.entity_id', $entity_nid, '=')
        ->condition('f.uid', $user_id[0], '=')
        ->execute()
        ->fetchAll();

      foreach ($all_fids as $fid) {
        $this->connection->delete('flag_stats')
          ->condition('fid', $fid->fid, '=')
          ->execute();
      }
    }
    elseif ($flag->getThirdPartySetting('flag_stats', 'unflag_stats', NULL) == 1) {
      // Add flag statestics entry on entity unflagged.
      // change from db_insert()
      $this->connection->insert('flag_stats')
        ->fields([
          'status' => 0,
          'flag_type' => $flag->getOriginalId(),
          'entity_id' => $entity_nid,
          'entity_type' => $enity_type,
          'uid' => $user_id[0],
      // Changes from REQUEST_TIME.
          'timestamp' => $this->time->getCurrentTime(),
        ])->execute();
    }
  }

  /**
   * Connect flag/unflag function to FlagEvents.
   */
  public static function getSubscribedEvents() {
    $events = [];
    $events[FlagEvents::ENTITY_FLAGGED][] = ['onFlag'];
    $events[FlagEvents::ENTITY_UNFLAGGED][] = ['onUnflag'];
    return $events;
  }

}
