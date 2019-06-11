<?php

namespace DevDeclan\Test\Support\Redkina\Factory;

use DevDeclan\Redkina\Repository;
use League\FactoryMuffin\Stores\ModelStore;

class RedkinaStore extends ModelStore
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @return Repository
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }

    /**
     * @param Repository $repository
     * @return RedkinaStore
     */
    public function setRepository(Repository $repository): RedkinaStore
    {
        $this->repository = $repository;
        return $this;
    }

    public function save($entity)
    {
        $result = $this->repository->save($entity);

        if (is_null($result)) {
            return false;
        }

        return true;
    }

    public function delete($model)
    {
        return $this->repository->delete($model);
    }

    public function safeDelete($model)
    {
        $i = array_search($model, $this->saved());
    }
}
