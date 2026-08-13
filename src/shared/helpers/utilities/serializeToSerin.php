<?php

use PostApi\shared\app\http\responses\success\serin\actions\Action;
use PostApi\shared\app\http\responses\success\serin\actions\Actions;
use PostApi\shared\app\http\responses\success\serin\actions\fields\Field;
use PostApi\shared\app\http\responses\success\serin\actions\fields\Fields;
use PostApi\shared\app\http\responses\success\serin\links\Link;
use PostApi\shared\app\http\responses\success\serin\links\Links;
use PostApi\shared\app\http\responses\success\serin\propeties\Propeties;
use PostApi\shared\app\http\responses\success\serin\propeties\Propety;
use PostApi\shared\app\http\responses\success\serin\SerinJson;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;


function serializeToSerin(object $entity)
{   
    $reflectedEntity = new ReflectionClass($entity);
    $entityPropeties = $reflectedEntity->getProperties();
    
    $serinPropeties = new Propeties();
    $serinFields = new Fields();
    $serinLinks = new Links();
    $serinActions = new Actions();

   
    foreach ($entityPropeties as $propety) {
        $propety->setAccessible(true);
        $propValue = $propety->getValue($entity);
        
       
        $serinPropety = buildSerinProperty($propety->getName(), $propValue);
        $serinPropeties->addPropety($serinPropety);

        $fieldType = $propety->getType() ? $propety->getType()->getName() : 'string';
        $serinField = new Field(name: $propety->getName(), type: $fieldType);
        $serinFields->addField($serinField);
    }

    $entityShortName = $reflectedEntity->getShortName();
    $entityPluralName = "{$entityShortName}s";

   
    $linksInfoArr = [
        "item" => Urls::transformRouteUrl("/$entityPluralName/{$entity->getId()}"),
        "collection" => Urls::transformRouteUrl("/$entityPluralName/"),
        "create $entityPluralName" => Urls::transformRouteUrl("/$entityPluralName/create"),
        "update $entityPluralName" => Urls::transformRouteUrl("/$entityPluralName/{$entity->getId()}"),
        "delete $entityPluralName" => Urls::transformRouteUrl("/$entityPluralName/{$entity->getId()}")
    ];

    foreach ($linksInfoArr as $rel => $url) {
        $serinLink = new Link(rel: [$rel], href: $url);
        $serinLinks->addLink($serinLink);
    }

   
    $actionsInfoArr = [
        "create $entityShortName" => [HttpMethodsType::POST, Urls::transformRouteUrl("/$entityPluralName/create")],
        "update $entityShortName" => [HttpMethodsType::PUT, Urls::transformRouteUrl("/$entityPluralName/{$entity->getId()}")],
        "delete $entityShortName" => [HttpMethodsType::DELETE, Urls::transformRouteUrl("/$entityPluralName/{$entity->getId()}")]
    ];

    foreach ($actionsInfoArr as $name => $values) {
        $serinAction = new Action(name: $name, method: $values[0], href: $values[1], type: "", fields: $serinFields);
        $serinActions->addAction($serinAction);
    }

    
    return new SerinJson(
        class: ["item", $entityShortName], 
        propeties: $serinPropeties, 
        enities: null, 
        actions: $serinActions, 
        links: $serinLinks
    );
}


function buildSerinProperty(string $name, mixed $value): Propety
{
    
    if ($value === null) {
        return new Propety($name, null);
    }

   
    if (is_object($value) && str_contains(get_class($value), 'PostApi\shared\app\http\responses\success\serin')) {
        return new Propety($name, $value);
    }

   
    if (is_array($value) || $value instanceof Traversable) {
        $itemPropertiesArray = [];
        foreach ($value as $item) {
            if (is_object($item)) {
                $nestedProps = new Propeties();
                $reflectedItem = new ReflectionClass($item);
                foreach ($reflectedItem->getProperties() as $p) {
                    $p->setAccessible(true);
                    $nestedProps->addPropety(buildSerinProperty($p->getName(), $p->getValue($item)));
                }
                $itemPropertiesArray[] = $nestedProps->propeties;
            } else {
                
                $itemPropertiesArray[] = $item;
            }
        }
        
        $propContainer = new Propety($name, null);
        $propContainer->addArrayValue($itemPropertiesArray);
        return $propContainer;
    }

    
    if (is_object($value)) {
        $reflectedObj = new ReflectionClass($value);
        $properties = $reflectedObj->getProperties();
        $shortName = $reflectedObj->getShortName();

       
        if (count($properties) === 1 && strtolower($properties[0]->getName()) === strtolower($shortName)) {
            $properties[0]->setAccessible(true);
            return buildSerinProperty($name, $properties[0]->getValue($value));
        }

      
        $objSerinProperties = new Propeties();
        foreach ($properties as $prop) {
            $prop->setAccessible(true);
            $objSerinProperties->addPropety(buildSerinProperty($prop->getName(), $prop->getValue($value)));
        }

        return new Propety($name, $objSerinProperties->propeties);
    }

   
    return new Propety($name, $value);
}

