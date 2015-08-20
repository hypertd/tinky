<?php

use Savvy\FrontendSearchBundle\Service\InvestmentDataCollection;

//mock investment data for testing

$mockInvestmentData = new InvestmentDataCollection();
$mockInvestmentData->merge(array(
    'network_id' => false,
    'development_id' => 1,
    'development' => '1',
    'development_prefix' => 'variable',
    'budget' => '500000',
    'budget_euro' => 7000000,
    'name' => 'matt',
    'email' => 'matt@savvycreativeuk.com',
    'agent_email' => 'matt@savvycreativeuk.com',
    'selling_type' => 'fraction',
    'capital_growth' => '6',
    'currency' => '1',
    'plot_number' => '',
));

$em = $container->get('doctrine')->getEntityManager();
$pds = $container->get('service.property_payment_data');
$pds->setInvestmentData($mockInvestmentData);

$propertyId = 216; //bugged
$fractionId = 10585;

$propertyRepository = $em->getRepository('OxygenPropertyBundle:Property');

$property_entity = $propertyRepository->findById($propertyId);
$property_entity = $property_entity[0];

$property = $em->createQuery('SELECT p FROM OxygenPropertyBundle:Property p WHERE p.id = :id')->setParameter('id', $propertyId)->getArrayResult();
$property_array = $property[0];

$property_type = $property_entity->getPropertyType();
$property_reference = $property_entity->getSellingType()->getReference();

echo 'property reference: '.$property_reference."\n";
$development = $property_entity->getPropertyType()->getDevelopment();
$management_fee = $development->getDevelopmentSetting('management_fees', 0);
$fraction_count = $property_entity->getFractionCount();

if ($property_entity->hasFractions()) {
    //get target fraction;
    $fraction = $property_entity->getFractionById($fractionId);

    //get payment data for fraction;
    if ($fraction) {
        $fpd = $pds->getPaymentDataForFraction($fraction);
    }

    echo "property has fractions ... \n";
    $fractions = $property_entity->getPropertyFractions();

    foreach ($fractions as $key => $fraction) {
        echo "{$key} fraction id: ".$fraction->getId()."\n";
    }
}
//locate a template
$parser = $container->get('templating.name_parser');
$locator = $container->get('templating.locator');

$path = $locator->locate($parser->parse('SearchBundle:variablePdfReport:pdf.report.html.twig'));

//because halves are now treated as fractions its using the fraction exchange rate which is defaulting to 1. because the default price of halves was euros
