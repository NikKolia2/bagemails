<?php
/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Ivan Klimchuk
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * Core extensions resolver
 *
 * @author Ivan Klimchuk <ivan@klimchuk.com>
 * @package Slackify
 * @subpackage build
 */

if (!$object->xpdo && !$object->xpdo instanceof modX) {
    return true;
}

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $object->xpdo->addExtensionPackage('bagemails', '[[++core_path]]components/bagemails/model/');
        $object->xpdo->addPackage('bagemails', MODX_CORE_PATH . 'components/bagemails/model/');
        $manager = $object->xpdo->getManager();
        $objects = [];
        $schemaFile = MODX_CORE_PATH . 'components/bagemails/model/schema/bagemails.mysql.schema.xml';
        if (is_file($schemaFile)) {
            $schema = new SimpleXMLElement($schemaFile, 0, true);
            if (isset($schema->object)) {
                foreach ($schema->object as $obj) {
                    $objects[] = (string)$obj['class'];
                }
            }
            unset($schema);
        }
        foreach ($objects as $class) {
            $table = $object->xpdo->getTableName($class);
            $sql = "SHOW TABLES LIKE '" . trim($table, '`') . "'";
            $stmt = $object->xpdo->prepare($sql);
            $newTable = true;
            if ($stmt->execute() && $stmt->fetchAll()) {
                $newTable = false;
            }
            // If the table is just created
            if ($newTable) {
                $manager->createObjectContainer($class);
            } else {
                // If the table exists
                // 1. Operate with tables
                $tableFields = [];
                $c = $object->xpdo->prepare("SHOW COLUMNS IN {$object->xpdo->getTableName($class)}");
                $c->execute();
                while ($cl = $c->fetch(PDO::FETCH_ASSOC)) {
                    $tableFields[$cl['Field']] = $cl['Field'];
                }
                foreach ($object->xpdo->getFields($class) as $field => $v) {
                    if (in_array($field, $tableFields)) {
                        unset($tableFields[$field]);
                        $manager->alterField($class, $field);
                    } else {
                        $manager->addField($class, $field);
                    }
                }
                foreach ($tableFields as $field) {
                    $manager->removeField($class, $field);
                }
                // 2. Operate with indexes
                $indexes = [];
                $c = $object->xpdo->prepare("SHOW INDEX FROM {$object->xpdo->getTableName($class)}");
                $c->execute();
                while ($row = $c->fetch(PDO::FETCH_ASSOC)) {
                    $name = $row['Key_name'];
                    if (!isset($indexes[$name])) {
                        $indexes[$name] = [$row['Column_name']];
                    } else {
                        $indexes[$name][] = $row['Column_name'];
                    }
                }
                foreach ($indexes as $name => $values) {
                    sort($values);
                    $indexes[$name] = implode(':', $values);
                }
                $map = $object->xpdo->getIndexMeta($class);
                // Remove old indexes
                foreach ($indexes as $key => $index) {
                    if (!isset($map[$key])) {
                        if ($manager->removeIndex($class, $key)) {
                            $object->xpdo->log(modX::LOG_LEVEL_INFO, "Removed index \"{$key}\" of the table \"{$class}\"");
                        }
                    }
                }
                // Add or alter existing
                foreach ($map as $key => $index) {
                    ksort($index['columns']);
                    $index = implode(':', array_keys($index['columns']));
                    if (!isset($indexes[$key])) {
                        if ($manager->addIndex($class, $key)) {
                            $object->xpdo->log(modX::LOG_LEVEL_INFO, "Added index \"{$key}\" in the table \"{$class}\"");
                        }
                    } else {
                        if ($index != $indexes[$key]) {
                            if ($manager->removeIndex($class, $key) && $manager->addIndex($class, $key)) {
                                $object->xpdo->log(modX::LOG_LEVEL_INFO,
                                    "Updated index \"{$key}\" of the table \"{$class}\""
                                );
                            }
                        }
                    }
                }
            }
        }
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        $object->xpdo->removeExtensionPackage('bagemails');
        break;
}

return true;
