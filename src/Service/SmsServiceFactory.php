<?php
/**
 * Created by PhpStorm.
 * User: keanor
 * Date: 02.03.16
 * Time: 18:59
 */
namespace ZfSms\Service;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use ZfSms\Adapter\AdapterInterface;
use ZfSms\ModuleOptions;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SmsServiceFactory
 * @package Sms\Service
 */
class SmsServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new SmsService();

        $moduleOptions = $serviceLocator->get(z);
        if (!$moduleOptions instanceof ModuleOptions) {
            throw new InvalidArgumentException('Unable create module options for sms module');
        }
        $service->setOptions($moduleOptions);

        $entityManager = $serviceLocator->get($moduleOptions->getEntityManagerName());
        if (!$entityManager instanceof EntityManager) {
            throw new InvalidArgumentException(sprintf(
                'Unable get entity manager by key "%s"',
                $moduleOptions->getEntityManagerName()
            ));
        }
        $service->setEntityManager($entityManager);

        $service->setAdapter($this->createAdapter($moduleOptions, $serviceLocator));

        return $service;
    }

    /**
     * Метод для создания адаптера
     *
     * @param ModuleOptions $options
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return AdapterInterface
     */
    public function createAdapter(ModuleOptions $options, ServiceLocatorInterface $serviceLocator)
    {
        $adapterConfig = $options->getAdapter();
        $adapterName = array_key_exists('name', $adapterConfig) ? $adapterConfig['name'] : 'noop';
        $adapterOptions = array_key_exists('options', $adapterConfig) ? $adapterConfig['options'] : [];

        $adapterClass = 'Sms\\Adapter\\' . ucfirst($adapterName) . 'Adapter';
        $adapter = new $adapterClass();
        if (!$adapter instanceof AdapterInterface) {
            throw new \InvalidArgumentException('Sms adapter must implement ' . AdapterInterface::class);
        }

        $adapter->setOptions($adapterOptions);
        if (isset($adapterConfig['dependency']) && is_array($adapterConfig['dependency'])) {
            foreach ($adapterConfig['dependency'] as $property => $dependency) {
                $dependency = $serviceLocator->get($dependency);
                $setter = 'set' . ucfirst($property);
                $adapter->$setter($dependency);
            }
        }

        return $adapter;
    }

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
        $moduleOptions = $container->get(ModuleOptions::class);
        $adapter = $container->get(AdapterInterface::class);

        return new SmsService($moduleOptions, $adapter);
    }
}
