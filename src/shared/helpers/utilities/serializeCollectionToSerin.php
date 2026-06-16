<?php


use PostApi\shared\app\http\responses\success\serin\enities\Entities;
use PostApi\shared\app\http\responses\success\serin\enities\Entity;
use PostApi\shared\app\http\responses\success\serin\links\Link;
use PostApi\shared\app\http\responses\success\serin\links\Links;
use PostApi\shared\app\http\responses\success\serin\propeties\Propeties;
use PostApi\shared\app\http\responses\success\serin\propeties\Propety;
use PostApi\shared\app\http\responses\success\serin\SerinJson;
use PostApi\shared\helpers\fecade\Urls;

function serializeCollectionToSerin(array $entities)
{        
    $serinPropeties = new Propeties();
    $countPropety = new Propety("count", count($entities));
    $serinPropeties->addPropety($countPropety);

    $serinEntities = new Entities();
    $serinLinks = new Links();
    $entityName = "";

    foreach ($entities as $entity) {
        $reflectedEntity = new ReflectionClass($entity);
        $entityName = $reflectedEntity->getShortName() . "s";

        $serinEntityPropeties = new Propeties();
        $serinEntityLinks = new Links();
        $entityPropeties = $reflectedEntity->getProperties();

        foreach ($entityPropeties as $entityPropety) {
            // تفعيل إمكانية قراءة الخصائص المحمية/الخاصة
            $entityPropety->setAccessible(true);
            $propValue = $entityPropety->getValue($entity);

            // عمل Normalize للبيانات القادمة من الـ Domain Model فقط
            $normalizedValue = normalizeValue($propValue);

            $entitySerinPropety = new Propety(name: $entityPropety->getName(), value: $normalizedValue);
            $serinEntityPropeties->addPropety($entitySerinPropety);
        }

        $linksInfoArr = [
            "item" => Urls::transformRouteUrl("/$entityName/{$entity->getId()}"),
            "collection" => Urls::transformRouteUrl("/$entityName/"),
            "create $entityName" => Urls::transformRouteUrl("/$entityName/create"),
            "update $entityName" => Urls::transformRouteUrl("/$entityName/{$entity->getId()}"),
            "delete $entityName" => Urls::transformRouteUrl("/$entityName/{$entity->getId()}")
        ];

        foreach ($linksInfoArr as $rel => $url) {
            $serinLink = new Link(rel: [$rel], href: $url);
            $serinEntityLinks->addLink($serinLink);
        }

        $serinEntity = new Entity(
            class: [$reflectedEntity->getShortName()],
            rel: ["item"],
            propeties: $serinEntityPropeties,
            links: $serinEntityLinks
        );
        $serinEntities->addEntity($serinEntity);
    }

    return new SerinJson(
        class: [$entityName, "collection"],
        propeties: $serinPropeties,
        enities: $serinEntities,
        actions: null,
        links: $serinLinks
    );
}


function normalizeValue(mixed $value): mixed
{
    if ($value === null) {
        return null;
    }


    if (is_object($value)) {
        $className = get_class($value);
        if (str_contains($className, 'PostApi\shared\app\http\responses\success\serin')) {
            return $value;
        }
    }


    if (is_array($value) || $value instanceof Traversable) {
        $arrayResult = [];
        foreach ($value as $key => $item) {
            $arrayResult[$key] = normalizeValue($item);
        }
        return $arrayResult;
    }


    if (is_object($value)) {
        $reflected = new ReflectionClass($value);
        $properties = $reflected->getProperties();
        $objectResult = [];


        $shortName = $reflected->getShortName();
        if (count($properties) === 1 && strtolower($properties[0]->getName()) === strtolower($shortName)) {
            $properties[0]->setAccessible(true);
            return normalizeValue($properties[0]->getValue($value));
        }

        foreach ($properties as $prop) {
            $prop->setAccessible(true);
            $objectResult[$prop->getName()] = normalizeValue($prop->getValue($value));
        }
        return $objectResult;
    }


    return $value;
}
