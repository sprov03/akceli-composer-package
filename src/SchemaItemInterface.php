<?php

namespace Akceli\Schema;

interface SchemaItemInterface
{
    /**
     * @param string $name
     */
    public function setName(string $name): self;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getCastTo(): string;

    /**
     * @return string
     */
    public function getDataType(): string;

    /**
     * @return string
     */
    public function getIsNullable(): bool ;
}