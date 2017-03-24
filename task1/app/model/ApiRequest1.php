<?php
namespace App\Model;

use App\Model\Traits\CastData;
use Nette\Http\Request;
use Nette\SmartObject;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Validators;

/**
 * Description of ApiRequest
 *
 * @author Tomas Grasl <grasl.t@centrum.cz>
 */
class ApiRequestTask1 implements IApiRequest
{
    use SmartObject;
    use CastData;
    
    /**
     * @var Request
     */
    private $_request;
    
    /**
     * @param Request $request
     */
    final function __construct(Request $request) {
        $this->_request = $request;
    }
    
    /**
     * @return InputDataEntity
     * @throws ApiBadRequestException
     */
    public function getRequestData() 
    {
        if($this->_request->method != 'POST') {
            throw new ApiBadRequestException('api accepts only POST request');
        }
        if($this->_request->headers['content-type'] != 'application/json') {
            throw new ApiBadRequestException('Bad header for the request api only accepts a header application/json but the request has header ' . $this->_request->headers['content-type']);
        }
        $entityBody = file_get_contents('php://input');        
        try {
            $body = Json::decode($entityBody);
        } catch (JsonException $ex) {
            throw new ApiBadRequestException('Fail json ' . $ex->getMessage());
        }
        if(!isset($body->citiesCount) || !Validators::isNumber($body->citiesCount) || $body->citiesCount === 0) {
            throw new ApiBadRequestException('In the request is not parameter citiesCount or have bad format.');
        }
        if(!isset($body->costOffers) || !is_array($body->costOffers) || empty($body->costOffers)) {
            throw new ApiBadRequestException('In the request is not parameter costOffers or have bad format.');
        }
        
        return $this->serealizeRequest($body);
    }
    
    /**
     * @param array $body
     * @return InputDataEntity
     */
    protected function serealizeRequest($body) 
    {
        $entity = new InputDataEntity();
        return $this->castDataToEntity($entity, $body);
    }
}