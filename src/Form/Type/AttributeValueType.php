<?php

namespace Gbenitez\Bundle\AttributeBundle\Form\Type;

use Gbenitez\Bundle\AttributeBundle\Entity\Attribute;
use Gbenitez\Bundle\AttributeBundle\Entity\AttributeValueInterface;
use Gbenitez\Bundle\AttributeBundle\Entity\Repository\AttributeRepository;
use Gbenitez\Bundle\AttributeBundle\Model\AttributeTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeValueType extends AbstractType
{
    /**
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $em;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param AttributeRepository $attributeRepository
     * @param \Doctrine\Common\Persistence\ObjectManager $em
     * @param TranslatorInterface $translator
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        \Doctrine\Common\Persistence\ObjectManager $em,
        TranslatorInterface $translator
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->em = $em;
        $this->translator = $translator;
    }

    public function getBlockPrefix()
    {
        return 'attribute_value';
    }
    public function getName()
    {
        return 'attribute_value';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            /** @var AttributeValueInterface $attributeValue */
            $attributeValue = $event->getData();
            $form = $event->getForm();

            $attribute = $attributeValue->getAttribute();
            $type = strtolower($attribute->getType());

            $configuration = (array)$attribute->getConfiguration();
            $configuration += array(
                'label' => $options['label'],
            );

            $configuration['constraints'] = $this->createConstraintValidations($attribute);
            $configuration['attr']['data-attribute-name'] = $attribute->getName();

            switch ($type) {
                case AttributeTypes::TEXT:
                case AttributeTypes::ENTITY:
                    $form->add('value', $type, $configuration);
                    break;
                case AttributeTypes::CHECKBOX:
                case AttributeTypes::MONEY:
                case AttributeTypes::NUMBER:
                case AttributeTypes::DATE:
                    $form->add('value', $type, $configuration);
                    break;
                case AttributeTypes::CHOICE:
                    $form->add('value', $type, $configuration);
                    break;
                default:
                    throw new InvalidArgumentException("No se reconoce el tipo de attributo \"{$type}\"");
            }
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /**@var AttributeValueInterface $attributeValue */
            $attributeValue = $event->getData();

            $attribute = $attributeValue->getAttribute();
            $type = strtolower($attribute->getType());
            $config = (array)$attribute->getConfiguration();

            if ($type == AttributeTypes::ENTITY) {
                if ($attributeValue->getValue()) {
                    $entity = $this->em->getRepository($config['class'])->find($attributeValue->getValue());

                    $attributeValue->setValue($entity);
                }

            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /**@var AttributeValueInterface $attributeValue */
            $attributeValue = $event->getData();
            $form = $event->getForm();

            $attribute = $attributeValue->getAttribute();
            $type = strtolower($attribute->getType());

            if ($type == AttributeTypes::ENTITY) {
                if ($attributeValue->getValue()) {
                    $attributeValue->setValue($attributeValue->getValue()->getId());
                }
            }
        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'data_class',
            'data',
            'attribute',
        ));

        $resolver->setAllowedTypes('attribute', Attribute::class);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['_attribute'] = $options['attribute'];
        $view->vars['_attribute_region_name'] = $options['attribute']->getRegion()->getName();
        $view->vars['container_class'] = $options['attribute']->getContainerClass();
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (!isset($view['value'])) {
            return;
        }

        $view->vars['valid'] = $view['value']->vars['valid'];
    }

    protected function createConstraintValidations(Attribute $attribute)
    {
        $constraints = [];
        $addRequiredConstraint = $this->isAttributeRequired($attribute);

        foreach ($attribute->getConstraints() as $classConstraint => $options) {
            $className = $this->resolveClassConstraint($classConstraint);
            $constraints[] = $constraint = new $className($options);

            if ($constraint instanceof NotBlank || $constraint instanceof NotNull) {
                $addRequiredConstraint = false;
            }
        }

        if ($addRequiredConstraint) {
            $message = $this->translator->trans('attribute.required', [
                '%name%' => $attribute->getName(),
                '%presentation%' => $attribute->getPresentation(),
            ], 'validators');

            $constraints[] = new NotBlank(['message' => $message]);
        }

        return $constraints;
    }

    private function resolveClassConstraint($class)
    {
        if (class_exists($class)) {
            return $class;
        }

        if (class_exists($sfClass = '\Symfony\Component\Validator\Constraints\\'.$class)) {
            return $sfClass;
        }

        throw new \Symfony\Component\Validator\Exception\InvalidArgumentException(
            'No existe la clase constraint "'.$class.'""'
        );
    }

    /**
     * @param Attribute $attribute
     * @return bool
     */
    private function isAttributeRequired(Attribute $attribute)
    {
        return isset($attribute->getConfiguration()['required']);
    }
}
