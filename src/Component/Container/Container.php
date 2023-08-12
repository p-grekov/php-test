<?php

namespace App\Component\Container;

use Closure;
use LogicException;
use ReflectionFunction;

final class Container implements ContainerInterface
{
    /**
     * @param array<string,mixed> $services
     */
    public function __construct(private array $services)
    {}

    public function get(string $id): ?object
    {
        if (false === array_key_exists($id, $this->services)) {
            return null;
        }

        if (gettype($this->services[$id]) !== 'object') {
            throw new LogicException(sprintf('Service %s not an object'));
        }

        if (get_class($this->services[$id]) === Closure::class) {
            $params = $this->getClosureArgs($this->services[$id]);
            $this->services[$id] = call_user_func_array(
                $this->services[$id],
                $params
            );
        }

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * @return array<string,object>
     * @param callable(): mixed $closure
     */
    private function getClosureArgs(callable $closure): array
    {
        $ref = new ReflectionFunction($closure);

        $result = [];
        foreach ($ref->getParameters() as $param) {
            $type = (string) $param->getType();

            if (!$param->isOptional() && !$this->has($type)) {
                throw new LogicException(sprintf('Cannot autowire %s service', $type));
            }

            $result[$param->getName()] = $this->get($type);
        }
        
        return $result;
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        foreach ($this->services as $service) {
            if (!is_object($service) || get_class($service) === Closure::class) {
                continue;
            }

            if ($service instanceof ClosureInterface) {
                $service->close();
            }
        }
    }
}