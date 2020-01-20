<?php

namespace Drupal\entity_test\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
// The Service class 4 this module.
use Drupal\entity_test\EntityTestMyInfo;

/**
 * Controller for the greetings simple message.
 */
class EntityTestController extends ControllerBase {

  /**
   * Put the services variables here.
   */
  protected $myinfo;

  /**
   * Constructor here, pass class property and initialize it.
   */
  public function __construct(EntityTestMyInfo $injected_var_value) {
    $this->myinfo = $injected_var_value;
  }

  /**
   * Create method here.
   */
  public static function create(ContainerInterface $container) {
    return new static(
        $container->get('entity_test.myinfo')

    );
  }

  /**
   * Greetings.
   * notice, this returns the method defined in service class being injected
   */
  public function cntrMethod1() {
    
    $request_time = \Drupal::time()->getRequestTime();
    $time = \Drupal::time()->getRequestTime();

    $format = \Drupal::service('date.formatter')->format($time, 'medium');
    return [
           '#markup' => $this->myinfo->getMyEntityInfo(),
            
    ];
  }

}
