<?php
/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace gbenitez\Bundle\AttributeBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Yaml\Yaml;


/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class ArrayToYamlTransformer implements DataTransformerInterface
{

    public function transform($value)
    {
        if (null === $value) {
            return;
        }

        return Yaml::dump($value);
    }

    public function reverseTransform($value)
    {
        if (null === $value) {
            return;
        }

        return Yaml::parse($value);
    }
}