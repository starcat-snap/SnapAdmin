<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Twig\TokenParser;

use SnapAdmin\Core\Framework\Adapter\Twig\Node\ReturnNode;
use SnapAdmin\Core\Framework\Log\Package;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

#[Package('core')]
final class ReturnNodeTokenParser extends AbstractTokenParser
{
    public function parse(Token $token): ReturnNode
    {
        $stream = $this->parser->getStream();
        $nodes = [];

        if (!$stream->test(Token::BLOCK_END_TYPE)) {
            $nodes['expr'] = $this->parser->getExpressionParser()->parseExpression();
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        return new ReturnNode($nodes, [], $token->getLine(), $this->getTag());
    }

    public function getTag(): string
    {
        return 'return';
    }
}
