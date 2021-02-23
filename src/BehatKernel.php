<?php

class BehatKernel extends Kernel
{
     /**
     * {@inheritdoc}
     */
    public function boot()
    {
        parent::boot();

        \Pimcore::setKernel($this);
    }

    protected function build(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        parent::build($container);

        $container->setParameter('pimcore.geoip.db_file', $container->getParameter('kernel.project_dir') . '/var/config/GeoLite2-City.mmdb');
    }
}
