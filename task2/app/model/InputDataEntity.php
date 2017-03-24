<?php
namespace App\Model;

/**
 * Description of InputDataEntity
 *
 * @author Tomas Grasl <grasl.t@centrum.cz>
 */
class InputDataEntity extends BaseEntity{

    /**
     * @var array
     */
    public $measurements;
    
    /**
     * @var array
     */
    public $samples;
}
