<?php

namespace DoctrineORMModule\Proxy\__CG__\Recruitment\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Registration extends \Recruitment\Entity\Registration implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'registrationId', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'registrationDate', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'registrationConfirmationDate', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'registrationConvocationDate', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'registrationAcceptanceDate', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'recruitment', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'preInterview', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'person', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'recruitmentKnowAbout');
        }

        return array('__isInitialized__', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'registrationId', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'registrationDate', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'registrationConfirmationDate', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'registrationConvocationDate', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'registrationAcceptanceDate', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'recruitment', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'preInterview', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'person', '' . "\0" . 'Recruitment\\Entity\\Registration' . "\0" . 'recruitmentKnowAbout');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Registration $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getRegistrationId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getRegistrationId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationId', array());

        return parent::getRegistrationId();
    }

    /**
     * {@inheritDoc}
     */
    public function getRegistrationDate($format = 'd/m/Y \\à\\s H:i:s')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationDate', array($format));

        return parent::getRegistrationDate($format);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegistrationDateAsDateTime()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationDateAsDateTime', array());

        return parent::getRegistrationDateAsDateTime();
    }

    /**
     * {@inheritDoc}
     */
    public function getRegistrationConfirmationDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationConfirmationDate', array());

        return parent::getRegistrationConfirmationDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegistrationConfirmationDate($registrationConfirmationDate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegistrationConfirmationDate', array($registrationConfirmationDate));

        return parent::setRegistrationConfirmationDate($registrationConfirmationDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegistrationConvocationDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationConvocationDate', array());

        return parent::getRegistrationConvocationDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegistrationConvocationDate($registrationConvocationDate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegistrationConvocationDate', array($registrationConvocationDate));

        return parent::setRegistrationConvocationDate($registrationConvocationDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getRecruitment()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRecruitment', array());

        return parent::getRecruitment();
    }

    /**
     * {@inheritDoc}
     */
    public function setRecruitment(\Recruitment\Entity\Recruitment $recruitment)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRecruitment', array($recruitment));

        return parent::setRecruitment($recruitment);
    }

    /**
     * {@inheritDoc}
     */
    public function getPerson()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPerson', array());

        return parent::getPerson();
    }

    /**
     * {@inheritDoc}
     */
    public function setPerson(\Recruitment\Entity\Person $person)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPerson', array($person));

        return parent::setPerson($person);
    }

    /**
     * {@inheritDoc}
     */
    public function getRecruitmentKnowAbout()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRecruitmentKnowAbout', array());

        return parent::getRecruitmentKnowAbout();
    }

    /**
     * {@inheritDoc}
     */
    public function getRegistrationAcceptanceDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationAcceptanceDate', array());

        return parent::getRegistrationAcceptanceDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegistrationAcceptanceDate($registrationAcceptanceDate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegistrationAcceptanceDate', array($registrationAcceptanceDate));

        return parent::setRegistrationAcceptanceDate($registrationAcceptanceDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegistrationNumber()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationNumber', array());

        return parent::getRegistrationNumber();
    }

    /**
     * {@inheritDoc}
     */
    public function getPreInterview()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPreInterview', array());

        return parent::getPreInterview();
    }

    /**
     * {@inheritDoc}
     */
    public function setPreInterview(\Recruitment\Entity\PreInterview $preInterview)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPreInterview', array($preInterview));

        return parent::setPreInterview($preInterview);
    }

    /**
     * {@inheritDoc}
     */
    public function addRecruitmentKnowAbout(\Doctrine\Common\Collections\Collection $arr)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addRecruitmentKnowAbout', array($arr));

        return parent::addRecruitmentKnowAbout($arr);
    }

    /**
     * {@inheritDoc}
     */
    public function removeRecruitmentKnowAbout(\Doctrine\Common\Collections\Collection $arr)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeRecruitmentKnowAbout', array($arr));

        return parent::removeRecruitmentKnowAbout($arr);
    }

}
