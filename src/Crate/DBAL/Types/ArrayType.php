<?php
/**
 * Licensed to CRATE Technology GmbH("Crate") under one or more contributor
 * license agreements.  See the NOTICE file distributed with this work for
 * additional information regarding copyright ownership.  Crate licenses
 * this file to you under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.  You may
 * obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  See the
 * License for the specific language governing permissions and limitations
 * under the License.
 *
 * However, if you have executed another commercial license agreement
 * with Crate these terms will supersede the license and you may use the
 * software solely pursuant to the terms of the relevant commercial agreement.
 */

namespace Crate\DBAL\Types;

use Crate\PDO\PDOCrateDB;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Types;

/**
 * Type that maps a PHP sequential array to an array SQL type.
 *
 */
class ArrayType extends Type
{

    const NAME = 'array';

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (!is_array($value) || (count($value) > 0 && !(array_keys($value) === range(0, count($value) - 1)))) {
            return null;
        }
        return $value;
    }

    public function convertToDatabaseValueSQL(string $sqlExpr, AbstractPlatform $platform): string
    {
        return parent::convertToDatabaseValueSQL($sqlExpr, $platform); // TODO: Change the autogenerated stub
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        return $value;
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     * @return string
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $options = !array_key_exists('platformOptions', $fieldDeclaration) ?
            array() : $fieldDeclaration['platformOptions'];
        return $this->getArrayTypeDeclarationSQL($platform, $fieldDeclaration, $options);
    }

    /**
     * Gets the SQL snippet used to declare an ARRAY column type.
     *
     * @param AbstractPlatform $platform
     * @param array $field
     *
     * @param array $options
     * @return string
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getArrayTypeDeclarationSQL(AbstractPlatform $platform, array $field, array $options)
    {
        $type = array_key_exists('type', $options) ? $options['type'] : Types::STRING;
        return 'ARRAY ( ' . Type::getType($type)->getSQLDeclaration($field, $platform) . ' )';
    }
}
