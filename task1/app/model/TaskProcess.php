<?php
namespace App\Model;

use Nette\SmartObject;
use stdClass;

/**
 * Description of TaskProcess
 *
 * @author Tomas Grasl <grasl.t@centrum.cz>
 */
class TaskProcess 
{
    use SmartObject;
    
    /**
     * @var array
     */
    private $_posible = [];

    /**
     * @param InputDataEntity $entity
     * @return array
     */
    public function init(InputDataEntity $entity)
    {
        $ids = [];
        array_map(function($value) use(&$ids) {
            $ids[] = $value->from;
            $ids[] = $value->to;
        }, $entity->costOffers);
        $stationsId = array_values(array_unique($ids));
        $statios = array_map(function($a){return $a = FALSE;}, $stationsId); 

        foreach ($stationsId as $id) {
            $this->_posible[$id]['statios'] = $statios;
            $this->_posible[$id]['totalCost'] = 0;
            $this->_posible[$id]['depotId'] = $id;
            
            unset($this->_posible[$id]['statios'][$id]);
            $this->calculateDirectDirection($entity->costOffers, $id);
            $this->calculateSubDirection($entity->costOffers, $id);
        }
        return $this->getBestVariat();
    }
    
    /**
     * @staticvar array $best
     * @return array
     */
    protected function getBestVariat()
    {
        static $best = [];
        
        foreach($this->_posible as $stationId => $stationValues) {
            if(count(array_unique($stationValues['statios'])) === 1) {
                unset($stationValues['statios']);
                $stationValues['feasible'] = TRUE;
                if(empty($best)) {
                    $best = $stationValues;
                }elseif($best['totalCost'] > $stationValues['totalCost']){
                    $best = $stationValues;
                }
            }
        }
        
        return $best;
    }


    /**
     * @param array $list
     * @param integer $id
     */
    protected function calculateDirectDirection($list, $id) 
    {
        foreach ($list as $values) {
            if($id === $values->from) {
                $this->setPosible($id, $values);
            }
        }
    }
    
    /**
     * @param array $list
     * @param integer $id
     */
    protected function calculateSubDirection($list, $id) 
    {
        foreach ($this->_posible[$id]['statios'] as $stationId => $station) {
            if(!$station) {
                foreach ($this->_posible[$id]['recommendedOffers'] as $sucessId => $value) {
                    foreach ($list as $listobj) {
                        if($listobj->from === $value->to && $listobj->to === $stationId) {
                            $this->setPosible($id, $listobj);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * @param integer $id
     * @param stdClass $value
     */
    protected function setPosible($id, $value)
    {
        if(!isset($this->_posible[$id]['recommendedOffers'][$value->to])) {
            $this->_posible[$id]['recommendedOffers'][$value->to] = $value;
            $this->_posible[$id]['totalCost'] += $value->price;
            $this->_posible[$id]['statios'][$value->to] = TRUE;
        }elseif(isset($this->_posible[$id]['recommendedOffers'][$value->to]) 
            && ($this->_posible[$id]['recommendedOffers'][$value->to]->price > $value->price)) {
            $this->_posible[$id]['totalCost'] -= $this->_posible[$id]['recommendedOffers'][$value->to]->price;
            $this->_posible[$id]['recommendedOffers'][$value->to] = $value;
            $this->_posible[$id]['totalCost'] += $value->price;
            $this->_posible[$id]['statios'][$value->to] = TRUE;
        }
   }
}