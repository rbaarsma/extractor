<?php

namespace Translation\Extractor\Visitor\Twig;

use Translation\Extractor\Model\SourceLocation;
use Translation\Extractor\Visitor\BaseVisitor;
use Twig_Environment;
use Twig_NodeInterface;

class TranslationFilter extends BaseVisitor  implements \Twig_NodeVisitorInterface
{
    public function enterNode(Twig_NodeInterface $node, Twig_Environment $env)
    {
        if (!$node instanceof \Twig_Node_Expression_Filter) {
            return $node;
        }

        $name = $node->getNode('filter')->getAttribute('value');
        if ('trans' !== $name && 'transchoice' !== $name) {
            return $node;
        }

        $idNode = $node->getNode('node');
        if (!$idNode instanceof \Twig_Node_Expression_Constant) {
            // We can only extract constants
            return $node;
        }

        $id = $idNode->getAttribute('value');
        $index = 'trans' === $name ? 1 : 2;
        $domain = 'messages';
        $arguments = $node->getNode('arguments');
        if ($arguments->hasNode($index)) {
            $argument = $arguments->getNode($index);
            if (!$argument instanceof \Twig_Node_Expression_Constant) {
                return $node;
            }

            $domain = $argument->getAttribute('value');
        }

        $source = new SourceLocation($id, $this->getAbsoluteFilePath(), $node->getLine(), ['domain' => $domain]);
        $this->collection->addLocation($source);

        return $node;
    }

    public function leaveNode(Twig_NodeInterface $node, Twig_Environment $env)
    {
        return $node;
    }

    public function getPriority()
    {
        return 0;
    }
}
