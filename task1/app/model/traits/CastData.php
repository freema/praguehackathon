<?php
namespace App\Model\Traits;

use App\Model\BaseEntity;

/**
 *
 * @author Tomas Grasl <grasl.t@centrum.cz>
 */
trait CastData {
    
    /**
     * @deprecated
     * @param BaseEntity $entity
     * @param DibiRow | array $data
     * @return BaseEntity
     */
    protected function pushDataToEntity($entity, $data) {
        return $this->castDataToEntity($entity, $data);
    }    
    
    /**
     * @param BaseEntity $entity
     * @param DibiRow | array $data
     * @return BaseEntity
     */
    protected function castDataToEntity($entity, $data) {
        foreach ($data as $key => $value) {
            $entity->{$key} = $value;
        }
        return $entity;
    }
}
