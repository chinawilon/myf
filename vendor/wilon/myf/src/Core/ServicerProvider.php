<?php

namespace Wilon\Myf\Core;

abstract class ServicerProvider
{

    abstract  public function register();

    public function __construct(protected Application $app)
    {
    }

    public function boot()
    {

    }
}