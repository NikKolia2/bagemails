<?php
/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Ivan Klimchuk <ivan@klimchuk.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * System settings resolver
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
        break;

    case xPDOTransport::ACTION_UPGRADE:
            if(!$object->xpdo->getObject('modSystemSetting', ['key' => 'bagemails.token'])){
                $ss = $object->xpdo->newObject('modSystemSetting');

                $ss->fromArray([
                    'namespace' => 'bagemails',
                    'xtype' => 'textfield',
                    'key' => 'bagemails.token',
                    'value' => '',
                ], '', true, true);
                $ss->save();
            }
        break;

    case xPDOTransport::ACTION_UNINSTALL:
        $object->xpdo->removeCollection('modSystemSetting', ['key:LIKE' => 'bagemails\.%']);
        break;
}

return true;
