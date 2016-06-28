<?php

namespace gbenitez\Bundle\AttributeBundle\Form\Type\Admin;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class TemplateNameType extends AbstractType
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getParent()
    {
        return 'choice';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $rootDir = $this->kernel->getRootDir();
        $bundleDir = $this->kernel->getBundle('AttributeBundle')->getPath();

        $dirs = [$bundleDir.'/Resources/views/field/'];

        if(is_dir($rootDir.'/Resources/AttributeBundle/views/field/')){
            $dirs[] = $rootDir.'/Resources/AttributeBundle/views/field/';
        }

        $files = Finder::create()
            ->files()
            ->name('/.html.twig$/')
            ->in($dirs);

        $choices = [];

        foreach($files as $file){
            $templateName = $file->getBasename('.html.twig');
            $choices[$templateName] = $templateName;
        }

        $resolver->setDefault('choices', $choices);
    }
}