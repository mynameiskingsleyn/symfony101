<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class VeryBadDesign //implements ContainerAwareInterface
{
    public $container;
    /**
    * @required
    */
    // public function setContainer(ContainerInterface $container = null)
    // {
    //     $this->container = $container->get(Greeting::class);
    //     //$this->container = $container->get('app.greeting');
    // }
    private $greeting;
    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
    }

    public function moreGreeting($greeted)
    {
        //return "good $greeted and again ".$this->container->greet($greeted);
        return "good $greeted and again ".$this->greeting->greet($greeted);
        //dd($this->greeting())
        //return " morning and ". $this->greeting->greet($greeted)." lets continue with everythings";
    }
}
