<?php

namespace App\Services;

class Registry
{
    protected array $services = [];

    /**
     * register product by model_code
     *
     * @param string $key
     * @param mixed $service
     */
    public function set(mixed $key,$value): void
    {
        $this->services[$key] = $value;
    }

    public function get(mixed $key) {
        return $this->services[$key] ?? null;
    }

    /**
     * verify if model_code exists
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->services[$key]);
    }

    /**
     * remove all register data
     *
     * @param string $key
     */
    public function flush(): void
    {
        $this->services= [];
        gc_collect_cycles();
    }
}
