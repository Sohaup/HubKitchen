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



// function serialzeToSerin(object $entity)
// {   
//     $reflectedEntity = new ReflectionClass($entity);
//     $entityPropeties = $reflectedEntity->getProperties();
//     $serinPropeties = new Propeties();
//     $serinLinks = new Links();
//     $serinFields = new Fields();
//     $serinActions = new Actions();
//     foreach ($entityPropeties as $propety) {
//         if (is_object($propety->getValue($entity))) {
//             $objProp = $propety->getValue($entity);
//             $reflectedObjProp = new ReflectionClass($objProp);
//             $objSerinPropeties = new Propeties();
//             $objPropeties = $reflectedObjProp->getProperties();
//             foreach ($objPropeties as $objPropety) {
//                 if (is_array($objPropety->getValue($objProp))) {
//                     $propCollection = $objPropety->getValue($objProp);
//                     $itemSerinPropetiesArr = [];
//                     foreach ($propCollection as $propItem) {
//                         $reflectItem = new ReflectionClass($propItem);
//                         $props = $reflectItem->getProperties();
//                         $itemSerinPropeties = new Propeties();
//                         foreach ($props as $prop) {
//                             $objSerinPropety = new Propety(name: $prop->getName(), value: $prop->getValue($propItem));
//                             $itemSerinPropeties->addPropety($objSerinPropety);
//                         }
//                         $itemSerinPropetiesArr[] = $itemSerinPropeties->propeties;
//                     }
//                     $propCollection = new Propety($propety->getName(), null);
//                     $propCollection->addArrayValue($itemSerinPropetiesArr);
//                     $serinPropeties->addPropety($propCollection);
//                 } else {
//                     $objSerinPropety = new Propety(name: $objPropety->getName(), value: $objPropety->getValue($objProp));
//                     $objSerinPropeties->addPropety($objSerinPropety);
//                     $serinPropety = new Propety($propety->getName(), $objSerinPropeties->propeties);
//                     $serinPropeties->addPropety($serinPropety);
//                 }
//             }
//         } else {
//             $serinPropety = new Propety($propety->getName(), $propety->getValue($entity));
//             $serinPropeties->addPropety($serinPropety);
//         }


//         $serinField = new Field(name: $propety->getName(), type: $propety->getType());
//         $serinFields->addField($serinField);
//     }
//     $entityName = "{$reflectedEntity->getShortName()}" . "s";
//     $linksInfoArr = [
//         "item" => Urls::transformRouteUrl("/$entityName/{$entity->getId()}"),
//         "collection" => Urls::transformRouteUrl("/$entityName/"),
//         "create $entityName" => Urls::transformRouteUrl("/$entityName/create"),
//         "update $entityName" => Urls::transformRouteUrl("/$entityName/{$entity->getId()}"),
//         "delete $entityName" => Urls::transformRouteUrl("/$entityName/{$entity->getId()}")
//     ];
//     foreach ($linksInfoArr as $rel => $url) {
//         $serinLink = new Link(rel: [$rel], href: $url);
//         $serinLinks->addLink($serinLink);
//     }
//     $entitySingleName = "{$reflectedEntity->getShortName()}";
//     $actionsInfoArr = [
//         "create $entitySingleName" => [HttpMethodsType::POST, Urls::transformRouteUrl("/$entityName/create")],
//         "update $entitySingleName" => [HttpMethodsType::PUT, Urls::transformRouteUrl("/$entityName/{$entity->getId()}")],
//         "delete $entitySingleName" => [HttpMethodsType::DELETE, Urls::transformRouteUrl("/$entityName/{$entity->getId()}")]
//     ];
//     foreach ($actionsInfoArr as $name => $values) {
//         $serinAction = new Action(name: $name, method: $values[0], href: $values[1], type: "", fields: $serinFields);
//         $serinActions->addAction($serinAction);
//     }
//     $serin = new SerinJson(class: ["item", $entitySingleName], propeties: $serinPropeties, enities: null, actions: $serinActions, links: $serinLinks);
//     return $serin;
// }

function serializeToSerin(object $entity)
{   
    $reflectedEntity = new ReflectionClass($entity);
    $entityPropeties = $reflectedEntity->getProperties();
    
    $serinPropeties = new Propeties();
    $serinFields = new Fields();
    $serinLinks = new Links();
    $serinActions = new Actions();

    // 1. Process top-level properties dynamically using a recursive builder
    foreach ($entityPropeties as $propety) {
        $propety->setAccessible(true);
        $propValue = $propety->getValue($entity);
        
        // Deeply serialize nested elements down to Propety instances
        $serinPropety = buildSerinProperty($propety->getName(), $propValue);
        $serinPropeties->addPropety($serinPropety);

        // Build action metadata field
        $fieldType = $propety->getType() ? $propety->getType()->getName() : 'string';
        $serinField = new Field(name: $propety->getName(), type: $fieldType);
        $serinFields->addField($serinField);
    }

    $entityShortName = $reflectedEntity->getShortName();
    $entityPluralName = "{$entityShortName}s";

    // 2. Build HATEOAS Links
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

    // 3. Build Hypermedia Actions
    $actionsInfoArr = [
        "create $entityShortName" => [HttpMethodsType::POST, Urls::transformRouteUrl("/$entityPluralName/create")],
        "update $entityShortName" => [HttpMethodsType::PUT, Urls::transformRouteUrl("/$entityPluralName/{$entity->getId()}")],
        "delete $entityShortName" => [HttpMethodsType::DELETE, Urls::transformRouteUrl("/$entityPluralName/{$entity->getId()}")]
    ];

    foreach ($actionsInfoArr as $name => $values) {
        $serinAction = new Action(name: $name, method: $values[0], href: $values[1], type: "", fields: $serinFields);
        $serinActions->addAction($serinAction);
    }

    // 4. Build and return root SerinJson container
    return new SerinJson(
        class: ["item", $entityShortName], 
        propeties: $serinPropeties, 
        enities: null, 
        actions: $serinActions, 
        links: $serinLinks
    );
}

/**
 * Recursively builds dynamic deep nesting structures using Propety instances.
 */
function buildSerinProperty(string $name, mixed $value): Propety
{
    // Handle true Null values
    if ($value === null) {
        return new Propety($name, null);
    }

    // Bypass check: If the value is already parsed into your engine components, return it
    if (is_object($value) && str_contains(get_class($value), 'PostApi\shared\app\http\responses\success\serin')) {
        return new Propety($name, $value);
    }

    // Handle Arrays and Collections (Iterables)
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
                // Scalar value arrays (e.g., list of strings or integers)
                $itemPropertiesArray[] = $item;
            }
        }
        
        $propContainer = new Propety($name, null);
        $propContainer->addArrayValue($itemPropertiesArray);
        return $propContainer;
    }

    // Handle Objects
    if (is_object($value)) {
        $reflectedObj = new ReflectionClass($value);
        $properties = $reflectedObj->getProperties();
        $shortName = $reflectedObj->getShortName();

        // Single-property Value Objects (e.g. wrapper objects where property name matches class name)
        if (count($properties) === 1 && strtolower($properties[0]->getName()) === strtolower($shortName)) {
            $properties[0]->setAccessible(true);
            return buildSerinProperty($name, $properties[0]->getValue($value));
        }

        // Complex Standard Object
        $objSerinProperties = new Propeties();
        foreach ($properties as $prop) {
            $prop->setAccessible(true);
            $objSerinProperties->addPropety(buildSerinProperty($prop->getName(), $prop->getValue($value)));
        }

        return new Propety($name, $objSerinProperties->propeties);
    }

    // Fallback: Default primitive types (string, int, bool, float)
    return new Propety($name, $value);
}

