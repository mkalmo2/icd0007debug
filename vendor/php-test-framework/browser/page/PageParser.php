<?php

namespace stf\browser\page;

use tplLib\HtmlLexer;
use tplLib\HtmlParser;
use tplLib\ParseException;
use tplLib\node\AbstractNode;
use tplLib\TreeBuilderActions;

class PageParser {

    private string $html;

    public function __construct(string $html) {
        $this->html = $html;
    }

    public function validate() : ValidationResult {
        try {

            $this->buildNodeTree($this->html);

        } catch (ParseException $ex) {
            return $this->createResult($ex);
        }

        return ValidationResult::success();
    }

    public function getNodeTree() : AbstractNode {
        return $this->buildNodeTree($this->html);
    }

    private function buildNodeTree($html) {
        $tokens = (new HtmlLexer($html))->tokenize();

        $builder = new TreeBuilderActions();

        (new HtmlParser($tokens, $builder))->parse();

        return $builder->getResult();
    }

    private function createResult(ParseException $ex): ValidationResult {
        $pos = $ex->pos;
        $textParsed = substr($this->html, 0, $pos);
        $lines = explode("\n", $textParsed);
        $lineNr = count($lines);
        $colNr = strlen($lines[$lineNr - 1]) + 1; // +1: starts from 1

        $start = max(0, $lineNr - 3);
        $end = $lineNr - 1;

        $source = '';
        foreach (range($start, $end) as $index) {
            $lineNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $source .= sprintf("%s %s\n", $lineNumber, $lines[$index]);
        }

        $source .= str_pad('^', $colNr + 4, ' ', STR_PAD_LEFT) . PHP_EOL;

        return ValidationResult::failure($ex->message, $lineNr, $colNr, $source);
    }

    public function getHtml(): string {
        return $this->html;
    }

}
