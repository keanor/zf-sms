<?php
namespace ZfSms\Adapter;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfSms\Adapter\Exception\InvalidArgumentException;
use ZfSms\ModuleOptions;

/**
 * Class AbstractAdapterFactory
 * @package ZfSms\Adapter
 */
class AbstractAdapterFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $container->get(ModuleOptions::class);
        $adapterConfig = $moduleOptions->getAdapter();
        if (!isset($adapterConfig['type'])) {
            throw new InvalidArgumentException('Missing required key "type" for adapter, read documentation');
        }

        if (!in_array(AdapterInterface::class, class_implements($adapterConfig['type']))) {
            throw new InvalidArgumentException(sprintf(
                'Sms adapter class "%s" must implement %s interface',
                $requestedName,
                AdapterInterface::class
            ));
        }

        $adapterClass = $adapterConfig['type'];
        $adapterOptions = isset($adapterConfig['options'])
            ? $adapterConfig['options'] : [];

        /** @var AdapterInterface $adapter */
        $adapter = new $adapterClass();
        $adapter->setOptions($adapterOptions);

        return $adapter;
    }
}