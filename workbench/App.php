<?php

namespace Tinky\Workbench;

use AppBundle\Controller\AuthenticatedUserController as BaseController;
use Tinky\Http\AppClient;

class App {
    public $authenticated, $authenticatedAdmin;
    
    public function __construct($container){
        
        $controller = new BaseController;
        $controller->setContainer($container);
        
        $this->container = $container;
        $this->controller = $controller;
        
        $this->authenticated = $this->container->get('service.user')->signInUser(
            $this->container->getParameter('cv_normaluser_username'),
            $this->container->getParameter('cv_normaluser_password')
        );

        $this->authenticatedAdmin = $this->container->get('service.user')->signInUserAdmin(
            $this->container->getParameter('cv_adminuser_username'),
            $this->container->getParameter('cv_adminuser_password')
        );

        $this->client = new AppClient($container, $container->get('session'));
        
        return $container;
    }
    
    public function login($username, $password){
        $this->authenticated = $this->container->get('service.user')->signInUser(
            $username,
            $password
        );
        
        $this->user = $this->userInfo();
        
        if($this->authenticated){
            return "$username logged-in\n";
        }
        
        return $this->authenticated;
    }
    
    public function userBanks(){
        return $this->container->get('service.wallet')->getBankAccounts();
    }
    
    public function userWallets(){
        return $this->client->userWallets();
    }
    
    public function userInfo(){
        return $this->container->get('user')->getUserInfo();
    }
    
    public function capitalizations($id){
         $filter = [
            'organization_id'  => [
                'operator' => '=',
                'value'    => $id,
            ]
        ];

        return $this->container->get('capitalization')->getCapitalizations($filter, true);
    }
    
    
    public function organization($id){
       return $this->container->get('service.organization')->getOrganization($id, true);
    }
    
    public function offering($id){
        $organizationData = $this->container->get('service.property')->getOrganizationData($id, true);
        return $organizationData['offering'];
    }
    
    
    public function organizationShareInfo($id){
        $organizationData = $this->container->get('service.property')->getOrganizationData($id, true);
        
        $offering        = $organizationData['offering'];
        $this->offering = $offering;
        
        $capitalizations = $organizationData['capitalizations'];
        
        $shares_purchased = 0;
        foreach($capitalizations as $capitalization){
            $shares_purchased +=  $capitalization['number_of_shares'];
        }
        
        $sharesOwned = $this->container->get('service.capitalization')->sharesOwned($capitalizations);

        // Users cannot own more that x shares in an organization - regardless of an offering on its own.
        $maxOwnership = $this->container->get('service.organization')->maxOwnership($offering);
        $remainingSharesAvailableForPurchase = $maxOwnership - $sharesOwned;
        
        $amountLeftToFund = $this->container->get('service.offering')->fundingRemaining($offering);
        
        return [
            'org_shares_purchased' => $shares_purchased,
            'shares_owned' => $sharesOwned,
            'max_ownership' => $maxOwnership,
            'remaining_shares' => $remainingSharesAvailableForPurchase,
            'left_to_fund' => $amountLeftToFund
        ];
    }
    
    public function investmentOptions($id){
        $organizationData = $this->container->get('service.property')->getOrganizationData($id, true);

        $organization    = $organizationData['organization'];
        $offering        = $organizationData['offering'];
        $capitalizations = $organizationData['capitalizations'];
        
        $permittedOwnershipAvailable = $this->container->get('service.organization')->ownershipAvailable(
            $offering,
            $capitalizations
        );
         
        $options = $this->container->get('service.investment')->getInvestmentFormOptions(
            $offering,
            $organization,
            $permittedOwnershipAvailable
        );
        
        return $options;
    }
    
    public function testEndpoint($endpoint){
        return $this->client->testEndpoint($endpoint);
    }
    
    public function reykerData(){
        return $this->container->get('reyker')->reykerData();
    }
}