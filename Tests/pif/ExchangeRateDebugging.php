<?php


//Sentry::authenticate(['email' => 'admin@email.com', 'password' => 'admin']);


//http://pif.app/property-details/791/0/roi-chart

$em = $container->get('doctrine')->getEntityManager();

$propertyId = 729; //bugged
//$propertyId = 791; //bugged

$propertyRepository = $em->getRepository('OxygenPropertyBundle:Property');

$property_entity = $propertyRepository->findById($propertyId);
$property_entity = $property_entity[0];
$property = $em->createQuery('SELECT p FROM OxygenPropertyBundle:Property p WHERE p.id = :id')->setParameter('id', $propertyId)->getArrayResult();

$property_array = $property[0];
$property_type = $property_entity->getPropertyType();
$property_reference = $property_entity->getSellingType()->getReference();
$development = $property_entity->getPropertyType()->getDevelopment();
$management_fee = $development->getDevelopmentSetting('management_fees', 0);

//locate a template
$parser = $container->get('templating.name_parser');
$locator = $container->get('templating.locator');

$template = 'Search';
$path = $locator->locate($parser->parse("SearchBundle:variable$template:roi.chart.$property_reference.html.twig"));

$currency = $em->getRepository('SearchBundle:Currency')->find(1);

$euros = $em->getRepository('SearchBundle:Currency')->find(1);
$euro_to_gbp_xchange_rate = $euros->getSiteSetting()->getValue();

$gbps = $em->getRepository('SearchBundle:Currency')->find(2);
$gbp_to_euro_xchange_rate = $gbps->getSiteSetting()->getValue();

echo $gbp_to_euro_xchange_rate."\n";
echo $management_fee / $gbp_to_euro_xchange_rate; //should always be this

//because halves are now treated as fractions its using the fraction exchange rate which is defaulting to 1. because the default price of halves was euros
