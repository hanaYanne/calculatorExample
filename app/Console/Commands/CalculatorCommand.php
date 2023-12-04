<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CalculatorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expression = $this->ask('Enter the expression:');
        $result = $this->calculate($expression);
        $this->info("Result: $result");
    }

    private function calculate($expression)
    {
        try {
            /// Tokenize the expression
            $tokens = preg_split('/([\+\-\*\/\(\) ])/', $expression, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

            // Parse and calculate
            return $this->parseExpression($tokens);
        } catch (\Throwable $e) {
            $this->error('Invalid expression. Please try again.');
            return null;
        }
    }

    private function parseExpression(&$tokens)
    {
        $value = $this->parseTerm($tokens);

        while (count($tokens) > 0) {
            $operator = array_shift($tokens);

            if ($operator === '+' || $operator === '-') {
                $term = $this->parseTerm($tokens);

                if ($operator === '+') {
                    $value += $term;
                } else {
                    $value -= $term;
                }
            } else {
                array_unshift($tokens, $operator);
                break;
            }
        }

        return $value;
    }

    private function parseTerm(&$tokens)
    {
        $value = $this->parseFactor($tokens);

        while (count($tokens) > 0) {
            $operator = array_shift($tokens);

            if ($operator === '*' || $operator === '/') {
                $factor = $this->parseFactor($tokens);

                if ($operator === '*') {
                    $value *= $factor;
                } else {
                    if ($factor != 0) {
                        $value /= $factor;
                    } else {
                        throw new \InvalidArgumentException('Division by zero');
                    }
                }
            } else {
                array_unshift($tokens, $operator);
                break;
            }
        }

        return $value;
    }

    private function parseFactor(&$tokens)
    {
        $token = array_shift($tokens);

        if ($token === '(') {
            $value = $this->parseExpression($tokens);

            if (count($tokens) == 0 || array_shift($tokens) !== ')') {
                throw new \InvalidArgumentException('Mismatched parentheses');
            }

            return $value;
        } elseif (is_numeric($token)) {
            return $token;
        } else {
            throw new \InvalidArgumentException('Invalid token: ' . $token);
        }
    }
}
