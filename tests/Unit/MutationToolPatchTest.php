<?php

declare(strict_types=1);

use Pest\Mutate\Mutation;
use Pest\Mutate\Mutators\Number\IncrementInteger;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use PhpParser\ParserFactory;
use Symfony\Component\Finder\SplFileInfo;

/** Temporary presence guard; retire with the function-default patch in issue 673. */
it('selects each complete function body for scalar default mutations', function (): void {
    $file = new SplFileInfo(__DIR__.'/../Fixtures/mutation-function-defaults.php', '', 'mutation-function-defaults.php');
    $ast = (new ParserFactory)->createForHostVersion()->parse($file->getContents());
    $traverser = new NodeTraverser;
    $traverser->addVisitor(new ParentConnectingVisitor);

    $ast = $traverser->traverse($ast);
    $defaults = (new NodeFinder)->find($ast, static fn ($node): bool => $node instanceof Int_ && $node->getAttribute('parent') instanceof Param);
    expect($defaults)->toHaveCount(4);
    $kinds = [];
    foreach ($defaults as $default) {
        $function = $default->getAttribute('parent')->getAttribute('parent');
        $kinds[] = $function::class;
        $original = clone $default;
        $default->value = 2;
        try {
            $mutation = Mutation::create($file, IncrementInteger::class, $original, $default, $ast);
            expect($mutation->startLine)->toBe($original->getStartLine())
                ->and($mutation->endLine)->toBe($function->getEndLine())
                ->and($mutation->endLine)->toBeGreaterThanOrEqual($mutation->startLine);
        } finally {
            $default->value = 1;
        }
    }

    expect($kinds)->toBe([
        Function_::class,
        ClassMethod::class,
        Closure::class,
        ArrowFunction::class,
    ]);
});
