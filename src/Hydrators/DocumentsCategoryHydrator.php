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
class DocumentsCategoryHydrator implements HydratorInterface
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

        /** @Many */
        $mongoData = isset($data['posts']) ? $data['posts'] : null;
        $return = $this->dm->getConfiguration()->getPersistentCollectionFactory()->create($this->dm, $this->class->fieldMappings['posts']);
        $return->setHints($hints);
        $return->setOwner($document, $this->class->fieldMappings['posts']);
        $return->setInitialized(false);
        if ($mongoData) {
            $return->setMongoData($mongoData);
        }
        $this->class->reflFields['posts']->setValue($document, $return);
        $hydratedData['posts'] = $return;

        /** @Field(type="string") */
        if (isset($data['name']) || (! empty($this->class->fieldMappings['name']['nullable']) && array_key_exists('name', $data))) {
            $value = $data['name'];
            if ($value !== null) {
                $return = (string) $value;
            } else {
                $return = null;
            }
            $this->class->reflFields['name']->setValue($document, $return);
            $hydratedData['name'] = $return;
        }
        return $hydratedData;
    }
}