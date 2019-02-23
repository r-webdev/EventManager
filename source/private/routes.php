<?php

use SlimFacades\App as SlimApp;
use SlimFacades\Route as SlimRoute;

// Default route
SlimRoute::get('', '\Mossengine\Web\v1\Controllers\DefaultController:getDefault')->add(new \Mossengine\Core\v1\Middleware\CoreMiddleware(SlimApp::self()));
SlimRoute::get('/', '\Mossengine\Web\v1\Controllers\DefaultController:getDefault')->add(new \Mossengine\Core\v1\Middleware\CoreMiddleware(SlimApp::self()));

// Support pages
SlimRoute::get('/account/register', '\Mossengine\Web\v1\Controllers\AccountController:getRegister')->add(new \Mossengine\Core\v1\Middleware\CoreMiddleware(SlimApp::self()));
SlimRoute::get('/account/confirm[/{email}]', '\Mossengine\Web\v1\Controllers\AccountController:getConfirm')->add(new \Mossengine\Core\v1\Middleware\CoreMiddleware(SlimApp::self()));
SlimRoute::get('/account/verify[/{uuid}/{token}]', '\Mossengine\Web\v1\Controllers\AccountController:getVerify')->add(new \Mossengine\Core\v1\Middleware\CoreMiddleware(SlimApp::self()));
SlimRoute::get('/account/forgot[/{email}]', '\Mossengine\Web\v1\Controllers\AccountController:getForgot')->add(new \Mossengine\Core\v1\Middleware\CoreMiddleware(SlimApp::self()));
SlimRoute::get('/account/reset[/{uuid}/{token}]', '\Mossengine\Web\v1\Controllers\AccountController:getReset')->add(new \Mossengine\Core\v1\Middleware\CoreMiddleware(SlimApp::self()));

// Error pages
SlimRoute::group('/error', function () {
    $this->get('/400', '\Mossengine\Web\v1\Controllers\DefaultController:get400');
    $this->get('/404', '\Mossengine\Web\v1\Controllers\DefaultController:get404');
    $this->get('/500', '\Mossengine\Web\v1\Controllers\DefaultController:get500');
})
    ->add(new \Mossengine\Core\v1\Middleware\CoreMiddleware(SlimApp::self()));

// Single Page App
SlimRoute::group('/app', function () {
    $this->get('', '\Mossengine\Web\v1\Controllers\AppController:getApp');
    $this->get('/{feature}', '\Mossengine\Web\v1\Controllers\AppController:getApp');
})->add(new \Mossengine\Core\v1\Middleware\CoreMiddleware(SlimApp::self()));

// Api routes
SlimRoute::group('/api', function () {

    SlimRoute::group('/v1', function () {
        // Default
        $this->get('', '\Mossengine\Api\v1\Controllers\DefaultController:getDefault');

        SlimRoute::group('/account', function () {
            $this->get('', '\Mossengine\Api\v1\Controllers\AccountController:schema')->setArgument('component', 'account');
            $this->post('', '\Mossengine\Api\v1\Controllers\AccountController:register')->setArgument('component', 'account');

            $this->get('/confirm/{email}', '\Mossengine\Api\v1\Controllers\AccountController:confirm')->setArgument('component', 'account');
            $this->get('/verify/{uuid}/{token}', '\Mossengine\Api\v1\Controllers\AccountController:verify')->setArgument('component', 'account');
            $this->get('/forgot/{email}', '\Mossengine\Api\v1\Controllers\AccountController:forgot')->setArgument('component', 'account');
            $this->post('/reset/{uuid}/{token}', '\Mossengine\Api\v1\Controllers\AccountController:reset')->setArgument('component', 'account');

            SlimRoute::group('', function () {
                $this->put('/password', '\Mossengine\Api\v1\Controllers\AccountController:password')->setArgument('component', 'account');
            })
                ->add(new \Mossengine\Core\v1\Middleware\AccountRequiredMiddleware(SlimApp::self()));
        });

        SlimRoute::group('/token', function () {

            $this->get('', '\Mossengine\Api\v1\Controllers\TokenController:schema')->setArgument('component', 'token');

            SlimRoute::group('', function () {
                $this->get('s', '\Mossengine\Api\v1\Controllers\TokenController:select')->setArgument('component', 'token');
                $this->post('', '\Mossengine\Api\v1\Controllers\TokenController:create')->setArgument('component', 'token');
            })
                ->add(new \Mossengine\Core\v1\Middleware\AccountRequiredMiddleware(SlimApp::self()));

            $this->get('/{token}', '\Mossengine\Api\v1\Controllers\TokenController:refresh')->setArgument('component', 'token');
            $this->delete('/{token}', '\Mossengine\Api\v1\Controllers\TokenController:delete')->setArgument('component', 'token');
        });
    })
        ->add(new \Mossengine\Core\v1\Middleware\AccountSetMiddleware(SlimApp::self()));
})
    ->add(new \Mossengine\Core\v1\Middleware\CoreMiddleware(SlimApp::self()));