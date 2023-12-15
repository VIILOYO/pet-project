<?php

namespace App\Domain\DTO\Abstracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JsonException;
use ReflectionClass;
use ReflectionProperty;

/**
 * Class DTO
 * @package App\Domain\DTO\Abstracts
 */
class DTO implements Arrayable, Jsonable, \JsonSerializable
{
    /** @var array<string, mixed> */
    protected array $casts = [];

    /** @var array<string, mixed> */
    private array $initialized_fields;

    /**
     * FilterInput constructor.
     * @param array<string, mixed> $fields
     */
    public function __construct(array $fields = [])
    {
        $this->fill($fields);
    }

    /**
     * @param array<string, mixed> $fields
     * @return $this
     */
    public function fill(array $fields): self
    {
        $vars = $this->getProperties();

        foreach ($vars as $variable) {
            $var = $variable->getName();
            if (isset($fields[$var])) {
                $value = $fields[$var];

                $value = $this->checkSetterValue($var, $value);

                if (isset($this->casts[$var])) {
                    $cast = $this->casts[$var];
                    $value = $this->prepareCastValue($cast, $value);
                } else if ($variable->getType()) {
                    /** @var \ReflectionNamedType $refType */
                    $refType = $variable->getType();
                    $type = $refType->getName();
                    if (class_exists($type)) {
                        if (is_subclass_of($type, DTO::class) && is_array($value)) {
                            $value = new $type($value);
                        }
                    }
                }

                $this->{$var} = $value;
            }
        }

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $vars = $this->getProperties();
        $array = [];

        foreach ($vars as $var) {
            $key = $var->getName();
            $value = $this->{$key} ?? null;

            if ($value instanceof Arrayable) {
                $value = $value->toArray();
            } else if (is_array($value)) {
                $value = array_map(
                    fn($item) => $item instanceof Arrayable ? $item->toArray() : $item,
                    $value
                );
            }

            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * @return array<string, mixed>
     */
    public function initialized(): array
    {
        $vars = $this->getProperties();
        $array = [];

        foreach ($vars as $var) {
            if ($var->isInitialized($this)) {
                $key = $var->getName();
                $value = $this->{$key};
                if ($value instanceof DTO) {
                    $array[$key] = $value->initialized();
                } else if ($value instanceof Arrayable) {
                    $array[$key] = $value->toArray();
                } else {
                    $array[$key] = $value;
                }
            }
        }

        return $array;
    }

    /**
     * @param array<int, string> $keys
     * @param bool $initialized
     * @return array<string, mixed>
     */
    public function only(array $keys, bool $initialized = true): array
    {
        $result = [];
        $value = $initialized ? $this->initialized() : $this->toArray();
        foreach ($keys as $key) {
            if (array_key_exists($key, $value)) {
                $result[$key] = $value[$key];
            }
        }

        return $result;
    }

    /**
     * @param string $name - name of property
     * @return bool
     */
    public function isInitialized(string $name): bool
    {
        return $this->initialized_fields[$name] ?? $this->initialized_fields[$name] = count(array_filter(
                $this->getProperties(),
                fn($property) => $property->isInitialized($this) && $property->getName() === $name
            )) > 0;
    }

    /**
     * @param int $options
     * @return string
     *
     * @throws JsonException
     */
    public function toJson($options = 0): string
    {
        return json_encode(
            $this->toArray(),
            $options
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return ReflectionClass
     */
    protected function reflection(): ReflectionClass
    {
        return new ReflectionClass(static::class);
    }

    /**
     * @return ReflectionProperty[]
     */
    protected function getProperties(): array
    {
        return $this->reflection()->getProperties(ReflectionProperty::IS_PUBLIC);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    protected function checkSetterValue(string $name, $value): mixed
    {
        $key = Str::studly($name);
        $method = "set{$key}Attribute";

        if (method_exists($this, $method)) {
            return $this->{$method}($value);
        }

        return $value;
    }

    /**
     * @param string|Closure $cast
     * @param mixed $value
     * @return mixed
     */
    protected function prepareCastValue($cast, $value): mixed
    {
        if (is_array($value)) {
            $value = array_map(
                fn($item) => is_callable($cast) ? $cast($value) : new $cast($item),
                $value
            );
        } else if ($value instanceof Collection) {
            $value->transform(
                fn($item) => is_callable($cast) ? $cast($value) : new $cast($item)
            );
        } else {
            $value = is_callable($cast) ? $cast($value) : new $cast($value);
        }

        return $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)json_encode(
            $this->toArray(),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
    }
}
