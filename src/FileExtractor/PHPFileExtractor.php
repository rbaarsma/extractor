<?php

namespace Translation\Extractor\FileExtractor;

use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\ParserFactory;
use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class PHPFileExtractor implements FileExtractor
{
    /**
     * @var Visitor[]|NodeVisitor[]
     */
    private $visitors;

    public function getSourceLocations(SplFileInfo $file, SourceCollection $collection)
    {
        $path = $file->getRelativePath();
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();
        foreach ($this->visitors as $v) {
            $v->init($collection, $file);
            $traverser->addVisitor($v);
        }

        try {
            $tokens = $parser->parse($file->getContents());
            $traverser->traverse($tokens);
        } catch (Error $e) {
            trigger_error(sprintf('Skipping file "%s" because of parse Error: %s. ', $path, $e->getMessage()));
        }
    }

    public function getType()
    {
        return 'php';
    }

    /**
     * @param NodeVisitor $visitor
     */
    public function addVisitor(NodeVisitor $visitor)
    {
        $this->visitors[] = $visitor;
    }
}