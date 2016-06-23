<?php

namespace gbenitez\Bundle\AttributeBundle\Form\Region;

use gbenitez\Bundle\AttributeBundle\Entity\Attribute;
use Symfony\Component\Form\FormView;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeRegionFilter
{
    /**
     * Retorna los formularios que sean atributos y que se hayan creado para la región indicada
     *
     * @param array|\Iterator|FormView $forms formularios de donde se va a filtrar los atributos
     * @param string|array $regions nombre de la region o regiones
     * @return array Campos de formulario que cumplen con las condiciones.
     */
    public function getAttributesByRegion($forms, $regions)
    {
        $a = iterator_to_array($this->filterForms($forms, (array)$regions));

        return $a;
    }

    /**
     * @param array|\Iterator|FormView $forms formularios de donde se va a filtrar los atributos
     * @param array $regions nombre de las regiones
     * @param null|\Iterator $filteredForms contenedor de los formularios que se van encontrando
     * @return \ArrayIterator
     */
    private function filterForms($forms, $regions, \Iterator $filteredForms = null)
    {
        $filteredForms || $filteredForms = new \ArrayIterator();

        /** @var FormView $formView */
        foreach ($this->toArray($forms) as $formView) {
            if ($this->isAttributeForm($formView) && $this->isAttributeInRegions($regions, $formView)) {
                $filteredForms->append($formView);
            }

            $this->filterForms($formView->children, $regions, $filteredForms);
        }

        return $filteredForms;
    }

    /**
     * Devuelve true si el formulario actual es un atributo
     * @param $formView
     * @return bool
     */
    private function isAttributeForm($formView)
    {
        return isset($formView->vars['_attribute']) && $formView->vars['_attribute'] instanceof Attribute;
    }

    /**
     * Convierte un iterador o un form_view a un array.
     *
     * @param array|\Iterator|FormView $forms formularios de donde se va a filtrar los atributos
     * @return array
     */
    private function toArray($forms)
    {
        if ($forms instanceof \Iterator || $forms instanceof FormView) {
            $forms = iterator_to_array($forms);
        }

        return $forms;
    }

    /**
     * Devuelve true si el atributo está definido en la región indicada.
     *
     * @param array $regions
     * @param FormView $formView
     * @return bool
     */
    private function isAttributeInRegions($regions, $formView)
    {
        return in_array($formView->vars['_attribute_region_name'], $regions);
    }
}