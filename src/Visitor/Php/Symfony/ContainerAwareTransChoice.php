<?php

namespace Translation\Extractor\Visitor\Php\Symfony;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;

class ContainerAwareTransChoice extends BasePHPVisitor  implements NodeVisitor
{
    public function beforeTraverse(array $nodes)
    {
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\MethodCall) {
            if (!is_string($node->name)) {
                return;
            }
            $name = $node->name;

            //If $this->get('translator')->trans('foobar')
            if ('transChoice' === $name) {
                $label = $this->getStringArgument($node, 0);
                $domain = $this->getStringArgument($node, 3);

                $source = new SourceLocation($label, $this->getAbsoluteFilePath(), $node->getAttribute('startLine'), ['domain' => $domain]);
                $this->collection->addLocation($source);
            }
        }
    }

    public function leaveNode(Node $node)
    {
    }

    public function afterTraverse(array $nodes)
    {
    }
}
