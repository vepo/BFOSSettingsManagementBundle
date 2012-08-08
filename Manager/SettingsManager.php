<?php

namespace BFOS\SettingsManagementBundle\Manager;

use BFOS\SettingsManagementBundle\Entity\Setting;
use Doctrine\ORM\EntityRepository;

class SettingsManager
{
    private $container;

    function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Creates a Setting entity.
     *
     * @param string  $name
     * @param string  $type
     * @param array  $value
     * @param array  $granted_editing_for
     *
     * @return Setting
     */
    public function createSetting($name, $type, $value = null, $granted_editing_for = null)
    {

        $em = $this->container->get('doctrine')->getEntityManager();
        $setting = new Setting();
        $setting->setName($name);
        $setting->setType($type);
        if($value) {
            $setting->setValue($value);
        }
        if($granted_editing_for) {
            $setting->setGrantedEditingFor($granted_editing_for);
        }

        $em->persist($setting);
        $em->flush();

        return $setting;
    }

    /**
     * Updates a Setting entity.
     *
     * @param string  $name
     * @param string  $type
     * @param array  $value
     * @param array  $granted_editing_for
     *
     * @return boolean
     */
    public function updateSetting($name, $type = null, $value = null, $granted_editing_for = null)
    {
        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->container->get('doctrine')->getEntityManager();
        /**
         * @var EntityRepository $rsetting
         */
        $rsetting = $em->getRepository('BFOSSettingsManagementBundle:Setting');

        if($name) {
            /**
             * @var Setting $esetting
             */
            $esetting = $rsetting->findOneBy(array('name'=>$name));
            if(!$esetting){
                return false;
            }
        } else {
            return false;
        }

        if($type) {
            $esetting->setType($type);
        }
        if($value) {
            $esetting->setValue($value);
        }
        if($granted_editing_for) {
            $esetting->setGrantedEditingFor($granted_editing_for);
        }

        $em->persist($esetting);
        $em->flush();

        return true;
    }

    /**
     * Deletes a Setting entity.
     *
     * @param string  $name
     *
     * @return boolean
     */
    public function deleteSetting($name)
    {
        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->container->get('doctrine')->getEntityManager();
        /**
         * @var EntityRepository $rsetting
         */
        $rsetting = $em->getRepository('BFOSSettingsManagementBundle:Setting');

        if($name) {
            /**
             * @var Setting $esetting
             */
            $esetting = $rsetting->findOneBy(array('name'=>$name));
            if(!$esetting){
                return false;
            }
        } else {
            return false;
        }

        $em->remove($esetting);
        $em->flush();

        return true;
    }


    /**
     * Get Setting entities by name.
     *
     * @param string $name
     *
     * @return array
     */
    public function getSettingByName($name)
    {
        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->container->get('doctrine')->getEntityManager();
        /**
         * @var EntityRepository $rsetting
         */
        $rsetting = $em->getRepository('BFOSSettingsManagementBundle:Setting');

        return $rsetting->findOneBy(array('name'=>$name));
    }

    /**
     * Get Setting value by name.
     *
     * @param string $name
     * @param null|mixed $default
     *
     * @return array
     */
    public function getValue($name, $default = null)
    {
        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->container->get('doctrine')->getEntityManager();
        /**
         * @var EntityRepository $rsetting
         */
        $rsetting = $em->getRepository('BFOSSettingsManagementBundle:Setting');
        /**
         * @var Setting $entity
         */
        $entity = $rsetting->findOneBy(array('name'=>$name));

        return $entity->getValue()!==null?$entity->getValue():$default;
    }

    /**
     * Get the type options
     *
     * @return array
     */
    public function getTypeOptions()
    {
        return array(
            'text'           => 'Text',
            'email_template' => 'E-mail Template'
        );
    }
}