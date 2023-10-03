<?php declare(strict_types=1);

namespace tplLib\node;

use RuntimeException;

class TagNode extends AbstractNode {

    protected array $attributes;
    protected bool $isVoidTag;
    protected bool $hasSlashClose;

    public function __construct($name, $attributes) {
        parent::__construct($name);
        $this->attributes = $attributes;
    }

    public function makeVoid() {
        $this->isVoidTag = true;
    }

    public function addSlashClose() {
        if (!$this->isVoidTag) {
            throw new RuntimeException('must be void tag');
        }

        $this->hasSlashClose = true;
    }

    public function render($scope) : string {
        return $this->isVoidTag
            ? $this->renderVoidTag($scope)
            : $this->renderBodyTag($scope);
    }

    public function renderVoidTag($scope) : string {
        $close = $this->hasSlashClose ? '/' : '';

        return sprintf('<%s%s%s>',
            $this->name, $this->attributeString($scope), $close);
    }

    public function renderBodyTag($scope) : string {

        $contents = $this->renderContents($scope);

        if ($this->name === 'tpl') {
            return $contents;
        }

        return sprintf('<%1$s%2$s>%3$s</%1$s>',
            $this->name, $this->attributeString($scope), $contents);
    }

    private function renderContents($scope) : string {
        $contents = '';
        foreach ($this->children as $child) {
            $contents .= $child->render($scope);
        }

        return $this->hasAttribute('tpl-trim-contents')
            ? trim($contents)
            : $contents;
    }

    protected function attributeString($scope) : string {
        $result = '';

        if ($this->hasAttribute('tpl-checked')) {
            if ($scope->evaluate($this->getExpression('tpl-checked'))) {
                $result .= ' checked="checked"';
            }
        }

        if ($this->hasAttribute('tpl-selected')) {
            if ($scope->evaluate($this->getExpression('tpl-selected'))) {
                $result .= ' selected="selected"';
            }
        }

        $attributesToSkip = [];
        if ($this->hasAttribute('tpl-class')) {
            $parts = preg_split('/\s+if\s+/', $this->getExpression('tpl-class'));

            if (count($parts) !== 2) {
                throw new RuntimeException(
                    "invalid expression for tpl-class");
            }

            $cssClasses = [];
            if ($this->hasAttribute("class")) {
                $cssClasses[] = $this->getExpression('class');
                $attributesToSkip[] = 'class';
            }

            $cssClass = trim($parts[0]);
            $expression = trim($parts[1]);

            if ($scope->evaluate($expression)) {
                $cssClasses[] = $cssClass;
            }

            if (!empty($cssClasses)) {
                $result .= sprintf(' class="%s"', join(' ', $cssClasses));
            }
        }

        foreach ($this->attributes as $key => $value) {
            if (strpos($key, 'tpl-') === 0) {
                continue;
            }
            if (in_array($key, $attributesToSkip)) {
                continue;
            }

            $result .= $this->formatAttribute($key,
                $scope->replaceCurlyExpression($value));
        }

        return $result;
    }

    public function getAttributeValue($name) : ?string {
        foreach ($this->attributes as $key => $value) {
            if (strtolower($key) === strtolower($name) && $value !== null) {
                return $this->stripQuotes($value);
            }
        }

        return null;
    }

    public function hasAttribute($name) : bool {
        foreach ($this->attributes as $key => $value) {
            if (strtolower($key) === strtolower($name)) {
                return true;
            }
        }

        return false;
    }

    private function formatAttribute($name, $value) : string {
        return $value === null
            ? sprintf(' %s', $name)
            : sprintf(' %s=%s', $name, $value);
    }

    protected function getExpression($attributeName) : string {
        return $this->stripQuotes($this->attributes[$attributeName]);
    }

    private function stripQuotes($string) : string {
        $string = preg_replace("/^['\"]/", '', $string);
        return preg_replace("/['\"]$/", '', $string);
    }

}
