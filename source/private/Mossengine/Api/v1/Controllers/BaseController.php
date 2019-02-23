<?php
namespace Mossengine\Api\v1\Controllers;

/**
 * Class BaseController
 * @package Mossengine\Api\v1\Controllers
 */
class BaseController extends \Mossengine\Core\v1\Controllers\CoreController
{

    /**
     * @var null
     */
    private $component = [];

    /**
     * CoreController constructor.
     * @param $container
     * @param null $arraySettings
     */
    public function __construct($container, $arraySettings = null) {
        // Call the BaseControllers __construct()
        parent::__construct($container, $arraySettings);

        // If components are provided then it is set.
        foreach (array_get($arraySettings, 'components', []) as $key => $component) {
            $this->component($key, $component);
        }
    }

    /**
     * @param $stringMethod
     * @param $arrayArgs
     * @return mixed
     */
    public function __call($stringMethod, $arrayArgs) {
        return $this->execute(array_get($arrayArgs, 0), array_get($arrayArgs, 1), array_get($arrayArgs, 2), array_get(array_get($arrayArgs, 2), 'component', 'invalid'), $stringMethod);
    }

    /**
     * @param $key
     * @param null $component
     * @return null
     */
    protected function component($key, $component = null) {
        return $this->component[$key] = (!empty($component) ? $component : array_get($this->component, $key, null));
    }


    /**
     * @param $request
     * @param $response
     * @param $args
     * @param $stringComponent
     * @param $stringFunction
     * @param array $arrayExecuteParameters
     * @return mixed
     */
    protected function execute($request, $response, $args, $stringComponent, $stringFunction, $arrayExecuteParameters = []) {
        // Check for a component
        if (!empty($this->component($stringComponent)) && in_array($stringFunction, array_keys($this->component($stringComponent)->schema()))) {
            // Check if schema request
            if ('schema' === $stringFunction) {
                return returnWithJSON($response, true, ['successfully returned route schema'], $this->component($stringComponent)->schema(), 200);
            }

            // get the array of parameters either as form data or json body payload
            $arrayParameters = filterInputData([
                'input' => [
                    'data' => array_merge(
                        (array)$request->getParsedBody(),
                        (array)$request->getParams(array_keys($this->component($stringComponent)->$stringFunction())),
                        (array)$args
                    )
                ],
                'keys' => [
                    'allow' => array_keys($this->component($stringComponent)->$stringFunction())
                ]
            ]);

            // set a returnable object on the array parameters if one exists in the execute parameters, this allows for passing through other returnables to each other.
            array_set($arrayParameters, 'returnable', array_get($arrayExecuteParameters, 'returnable', null));

            // Attempt to execute the component function
            $this->component($stringComponent)->$stringFunction($arrayParameters);

            // Check results of the function and return appropriate payload.
            if ($this->component($stringComponent)->returnable()->hasFailed()) {
                return returnWithJSON($response, false, $this->component($stringComponent)->returnable()->errors(), null, ($this->component($stringComponent)->returnable()->hasExceptions() ? array_get($this->component($stringComponent)->schema(), $stringFunction . '.http.status.exception', 500) : array_get($this->component($stringComponent)->schema(), $stringFunction . '.http.status.error', 400)), $this->component($stringComponent)->returnable()->exceptions());
            } else {
                return returnWithJSON($response, true, $this->component($stringComponent)->returnable()->reasons(), $this->component($stringComponent)->returnable()->data(), array_get($this->component($stringComponent)->schema(), $stringFunction . '.http.status.success', 200));
            }
        }

        return returnWithJSON($response, false, ['invalid route component function'], null, 400);
    }
}