<?php
namespace App\Model;

use Nette\SmartObject;

/**
 * Description of TaskProcess
 *
 * @author Tomas Grasl <grasl.t@centrum.cz>
 */
class TaskProcess 
{
    use SmartObject;
    
    /**
     * @todo resit dulezitost raitingu
     * @var array
     */
    private $_raiting = [
        "noise-level"       => 1,
        "brake-distance"    => 1,
        "vibrations"        => 1,        
    ];
    
    /**
     * @param InputDataEntity $entity
     * @return array
     */
    public function init(InputDataEntity $entity)
    {
        $return = [];
        $type = [];
        foreach ($entity->measurements as $key => $value) {
            $type[$key] = (string) $value->{'type'};
        }
        
        foreach ($entity->samples as $sample) {
            $keys = $this->findBestVariant($entity->measurements, $sample);
            foreach ($keys as $key) {
                $return[] = [
                    'id'    => $sample->{'id'},
                    'type'  => $type[$key],
                ];
            }
        }
        
        return $return;
    }
    
    /**
     * @param array $list
     * @param stdClass $sample
     * @return array
     */
    protected function findBestVariant($list, $sample)
    {
        $return = [];
        $sum = [];
        $raiting = $this->createRaiting($list, $sample);
        foreach ($raiting as $key => $value) {
            $sum[$key] = array_sum($value);
        }        
        $maxRaiting = max($sum);
        foreach ($sum as $key => $value) {
            if($maxRaiting === $value) {
                $return[] = $key;
            }
        }
        return $return;
    }
    
    /**
     * @param array $list
     * @param stdClass $sample
     * @return array
     */
    protected function createRaiting(array $list, $sample) 
    {
        $noiseLevel = [];
        $brakeDistance = [];
        $vibrations = [];
        foreach($list as $key => $value) {
            $noiseLevel[$key] = $value->{'noise-level'}; 
            $brakeDistance[$key] = $value->{'brake-distance'};
            $vibrations[$key] = $value->{'vibrations'};
        }
        $closesNoiseLevelValue = self::getClosest($sample->{'noise-level'}, $noiseLevel);
        $closesBrakeDistanceValue = self::getClosest($sample->{'brake-distance'}, $brakeDistance);
        $closesVibrationsValue = self::getClosest($sample->{'vibrations'}, $vibrations);
       
        foreach ($list as $value) {
            $return[] = [
                'noise-level'       => $this->getRaiting($value, 'noise-level', $closesNoiseLevelValue),
                'brake-distance'    => $this->getRaiting($value, 'brake-distance', $closesBrakeDistanceValue),
                'vibrations'        => $this->getRaiting($value, 'vibrations', $closesVibrationsValue),
            ];
        }
        return $return;        
    }
    
    /**
     * @param array $value
     * @param string $key
     * @param integer | float $closesValue
     * @return integer
     */
    protected function getRaiting($value, $key, $closesValue) 
    {
        if($value->{$key} === $closesValue) {
            return $this->_raiting[$key]; 
        }else{
            return 0;
        }
    }
    
    /**
     * @param integer $search
     * @param array $arr
     * @return integer
     */
    protected static function getClosest($search, array $arr) 
    {
        $closest = NULL;
        foreach ($arr as $value) {
            if ($closest === NULL || abs($search - $closest) > abs($value - $search)) {
                $closest = $value;
            }
        }
       return $closest;
    }    
}