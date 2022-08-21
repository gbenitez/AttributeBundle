<?php
/**
 * Created by
 * User: gbenitez
 * Date: 12/08/15
 * Time: 08:34
 */

namespace Gbenitez\Bundle\AttributeBundle\Model;


class AttributeTypes
{
    const CHECKBOX   = 'checkbox';
    const CHOICE     = 'choice';
    const MONEY      = 'money';
    const NUMBER     = 'number';
    const PERCENTAGE = 'percent';
    const TEXT       = 'text';
    const ENTITY       = 'entity';
    const DATE       = 'date';

    public static function getChoices()
    {
        return array(
            self::CHECKBOX   => 'Checkbox',
            self::CHOICE     => 'Choice',
            self::MONEY      => 'Money',
            self::NUMBER     => 'Number',
            self::PERCENTAGE => 'Percentage',
            self::TEXT       => 'Text',
            self::ENTITY       => 'entity',
            self::DATE       => 'date',
        );
    }
}
