<?php

namespace Gbenitez\AttributeBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;

#[AsCommand(
    name: 'gbenitez:attribute:diagnostic',
    description: 'Diagnóstico del Attribute Bundle'
)]
class DiagnosticCommand extends Command
{
    public function __construct(
        private readonly RouterInterface $router
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Diagnóstico del Attribute Bundle');

        // Verificar rutas
        $routes = $this->router->getRouteCollection();
        $attributeRoutes = [];
        
        foreach ($routes as $name => $route) {
            if (str_contains($name, 'gbenitez_attribute')) {
                $attributeRoutes[$name] = [
                    'path' => $route->getPath(),
                    'methods' => $route->getMethods() ?: ['GET'],
                ];
            }
        }

        if (empty($attributeRoutes)) {
            $io->error('No se encontraron rutas del Attribute Bundle');
            $io->note('Verifica que las rutas estén cargadas en config/routes.yaml');
            return Command::FAILURE;
        }

        $io->success('Rutas del Attribute Bundle encontradas:');
        foreach ($attributeRoutes as $name => $info) {
            $io->writeln(sprintf(
                '  %s: %s [%s]',
                $name,
                $info['path'],
                implode('|', $info['methods'])
            ));
        }

        // Verificar que el controlador existe
        $controllerClass = 'Gbenitez\AttributeBundle\Controller\AttributeController';
        if (!class_exists($controllerClass)) {
            $io->error("La clase del controlador {$controllerClass} no existe");
            return Command::FAILURE;
        }

        $io->success('Controlador encontrado: ' . $controllerClass);

        $io->success('El Attribute Bundle está correctamente configurado');
        
        return Command::SUCCESS;
    }
}
