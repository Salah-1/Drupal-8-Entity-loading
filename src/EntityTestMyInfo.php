<?php
// this is the service class be called by dependency Inje

namespace Drupal\entity_test;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Component\Datetime;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Config\ConfigFactoryInterface; // for loading cofigForm stuff

use Drupal\Core\Entity\Query;  // For Querying Entity
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Messenger\MessengerInterface;
 

/**
 * Prepare the property as service, This is the service itself
 * It will be Injected into the Controller using constructor and create method
 */
class EntityTestMyInfo {
    
    use StringTranslationTrait;

    /**
    * @var \Drupal\Core\Config\ConfigFactoryInterface 
    */
    protected $configFactory;
    

     /**
    * @EntityTestMyInfo constructor.
    * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
    */
    public function  __construct (ConfigFactoryInterface $config_factory) {
        $this->configFactory = $config_factory;
        
    }


    /**
    * Returns the property valaues 
    * Check if something has been saved at the config form, then load it
    */
    public function getMyEntityInfo() {  

        $config = $this->configFactory->get('entity_test.my_config_obj_name1');
        $MyInfo = $config->get('MyInfo');
        if ($MyInfo != "") {
            return $MyInfo; // if there is value in config form return it
        }


        //$time = new \DateTime(); // Always works, but want use Drupals

        $time = new DrupalDateTime(); 
        // for now using this call, but later changing to DI
        $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
         
        $query->condition('type', 'car')
                ->condition('status', 'TRUE')
                ->range(0,10)
                ->sort('created', 'DESC');
                $ids = $query->execute();
               
               //xxxxxxxx
               $id = 2; // node of car type 
               $some_node =   \Drupal::entityTypeManager()->getStorage('node')->load($id);
               $en_type = $some_node->getType();
               $en_id =  $some_node->id(); 
               $en_label = $some_node->label();
               $en_bundle = $some_node->bundle();
               $en_title = $some_node->get('title');

               $msg = 'Showing the Results of \Drupal::entityTypeManager()->getStorage(node)->load($id)';

               $msg .= "<br />: Entity Type  is: ".$en_type.', Label is: '.$en_label.
               ', <br /> ID is: '.$en_id.
               ', bundle is: '.$en_bundle;
               //', title is:'.$en_title::to_string();

               \Drupal::messenger()->addMessage('Msg from Messenger service '); 
               \Drupal::messenger()->addError('Error from Messenger service '); 

               return $msg;
              //Kint();

               
              

    }   // End get method

     

    /**
     * Returns render array of MyInfo.
     */
    public function getMyInfoComponent() {
        $render = [
            '#theme' => 'entity_test_MyInfo',
        ];

        $config = $this->configFactory->get('entity_test.custom_MyInfo');
        $MyInfo = $config->get('MyInfo');

        if ($MyInfo != "") {
            $render['#MyInfo'] = $MyInfo;
            $render['#overridden'] = TRUE;
            return $render;
        }

        $time = new DrupalDateTime();    
        $render['#target'] =  $this->t('world');           
        
        if ((int) $time->format('G') >= 00 && (int) $time->format('G') < 12) {
            $render['#MyInfo'] =  $this->t('Good Morning'); 
            return $render;
        }

        if ((int) $time->format('G') >= 12 && (int) $time->format('G') < 18) {
            $render['#MyInfo'] =  $this->t('Good Afternoon'); 
            return $render;
        }

        if ((int) $time->format('G') >= 18) {
            $render['#MyInfo'] =  $this->t('Good Evening'); 
            return $render;
        }

    } // END method



}       // End the class