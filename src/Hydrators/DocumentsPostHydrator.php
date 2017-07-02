<?php

namespace Hydrators;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\Query\Query;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class DocumentsPostHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="id") */
        if (isset($data['_id']) || (! empty($this->class->fieldMappings['id']['nullable']) && array_key_exists('_id', $data))) {
            $value = $data['_id'];
            if ($value !== null) {
                $return = $value instanceof \MongoId ? (string) $value : $value;
            } else {
                $return = null;
            }
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @ReferenceOne */
        if (isset($data['category'])) {
            $reference = $data['category'];
            if (isset($this->class->fieldMappings['category']['storeAs']) && $this->class->fieldMappings['category']['storeAs'] === ClassMetadataInfo::REFERENCE_STORE_AS_ID) {
                $className = $this->class->fieldMappings['category']['targetDocument'];
                $mongoId = $reference;
            } else {
                $className = $this->unitOfWork->getClassNameForAssociation($this->class->fieldMappings['category'], $reference);
                $mongoId = $reference['$id'];
            }
            $targetMetadata = $this->dm->getClassMetadata($className);
            $id = $targetMetadata->getPHPIdentifierValue($mongoId);
            $return = $this->dm->getReference($className, $id);
            $this->class->reflFields['category']->setValue($document, $return);
            $hydratedData['category'] = $return;
        }

        /** @Field(type="int") */
        if (isset($data['views']) || (! empty($this->class->fieldMappings['views']['nullable']) && array_key_exists('views', $data))) {
            $value = $data['views'];
            if ($value !== null) {
                $return = (int) $value;
            } else {
                $return = null;
            }
            $this->class->reflFields['views']->setValue($document, $return);
            $hydratedData['views'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['title']) || (! empty($this->class->fieldMappings['title']['nullable']) && array_key_exists('title', $data))) {
            $value = $data['title'];
            if ($value !== null) {
                $return = (string) $value;
            } else {
                $return = null;
            }
            $this->class->reflFields['title']->setValue($document, $return);
            $hydratedData['title'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['description']) || (! empty($this->class->fieldMappings['description']['nullable']) && array_key_exists('description', $data))) {
            $value = $data['description'];
            if ($value !== null) {
                $return = (string) $value;
            } else {
                $return = null;
            }
            $this->class->reflFields['description']->setValue($document, $return);
            $hydratedData['description'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['body']) || (! empty($this->class->fieldMappings['body']['nullable']) && array_key_exists('body', $data))) {
            $value = $data['body'];
            if ($value !== null) {
                $return = (string) $value;
            } else {
                $return = null;
            }
            $this->class->reflFields['body']->setValue($document, $return);
            $hydratedData['body'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['image']) || (! empty($this->class->fieldMappings['image']['nullable']) && array_key_exists('image', $data))) {
            $value = $data['image'];
            if ($value !== null) {
                $return = (string) $value;
            } else {
                $return = null;
            }
            $this->class->reflFields['image']->setValue($document, $return);
            $hydratedData['image'] = $return;
        }

        /** @Field(type="date") */
        if (isset($data['created'])) {
            $value = $data['created'];
            if ($value === null) { $return = null; } else { $return = \Doctrine\ODM\MongoDB\Types\DateType::getDateTime($value); }
            $this->class->reflFields['created']->setValue($document, clone $return);
            $hydratedData['created'] = $return;
        }
        return $hydratedData;
    }
}