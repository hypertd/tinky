<?php
namespace Tinky\Workbench\Http;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Guzzle\Http\Client;
use ClientBundle\Service\BaseClientService;

/**
 * HTTP client based on Guzzle
 */
class GuzzleClient extends Client
{
    public function __construct($endpoint, $config = null)
    {
        parent::__construct($endpoint, $config); // second param is config which is null and can be set via setConfig method
        $this->setSslVerification(false, 0, 0);
    }
}

class AppClient extends BaseClientService {
    
    public function __construct(ContainerInterface $container, Session $session){
        parent::__construct($container, $session);
        $this->container = $container;
        $this->userToken = $this->container->get('user')->getToken();
    }
    
    public function getRequest($endpoint){
        return $this->makeGetRequest($endpoint, $this->userToken);
    }
    
    
    public function post($endpoint, array $parameters = [], array $queryParameters = []){
        $query = $this->buildQueryParameters($queryParameters);

        $client  = new GuzzleClient('https://resort-crowd-hypertd.c9users.io');
        $request = $client->post("{$endpoint}?{$query}");

        $json_parameter = json_encode($parameters);
        $request->setBody($json_parameter, 'application/json');
        
        $response     = $request->send();
        $responseBody = $response->getBody();
        return $this->makeRequest($request);
    }
    
     public function get($endpoint, array $parameters = [], array $queryParameters = []){
        $query = $this->buildQueryParameters($queryParameters);

        $client  = new GuzzleClient('https://resort-crowd-hypertd.c9users.io');
        $request = $client->post("{$endpoint}?{$query}");

        $json_parameter = json_encode($parameters);
        $request->setBody($json_parameter, 'application/json');
        
        $response     = $request->send();
        $responseBody = $response->getBody();

        return $this->makeRequest($request);
    }
    
    public function userWallets(){
        return $this->makeGetRequest("/self/mangopay/wallets", $this->userToken);
    }
    
     public function simulateMangopayApproval(){
        $client  = $this->container->get('client');
        $request = $client->post("https://resort-crowd-hypertd.c9users.io/hooks/kyc-notification");
        
        $parameters = [
            "Status" => "ENABLED",
            "Validity" => "VALID",
            "EventType" => "KYC_SUCCEEDED"
        ];

        $json_parameter = json_encode($parameters);
        $request->setBody($json_parameter, 'application/json');

        return $this->makeRequest($request);
    }
}
