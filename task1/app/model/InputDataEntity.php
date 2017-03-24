<?php
namespace App\Model;

/**
 * Description of InputDataEntity
 *
 * @author Tomas Grasl <grasl.t@centrum.cz>
 */
class InputDataEntity extends BaseEntity{

    /**
     * @var integer
     */
    public $citiesCount;
    
    /**
     * @var array
     */
    public $costOffers;
}
