<?php

namespace DSL\EntityDescriptor;

final class Method {
    public const METHOD_NAME_BEGIN = "method__";

    protected string $_name;
    protected string $_comment;

    /**
     * @var \DSL\EntityDescriptor\Method\Parameter[] $_parameters
     */
    protected array $_parameters = [];

    protected function __construct() { }

    /**
     * @param \ReflectionMethod $method
     *
     * @return static
     */
    public static function parse(\ReflectionMethod $method) : static {
        $instance = new static();

        $instance->_name = substr($method->getName(), strlen(static::METHOD_NAME_BEGIN));
        $instance->_comment = \DSL\Helpers::extractDocComment($method->getDocComment() ?: '') ?? "### Method have no description ###";
        $instance->_parameters = array_map(
            fn(\ReflectionParameter $parameter) => new \DSL\EntityDescriptor\Method\Parameter(
                $parameter->getName(),
                $parameter->getType(),
                $parameter->isOptional(),
                $parameter->allowsNull(),
            ),
            $method->getParameters()
        );

        return $instance;
    }

}
