<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CalculatorCommandTest extends TestCase
{
    public function testAddition()
    {
        $this->artisan('calculator')
            ->expectsQuestion('Enter the expression:', '1+1')
            ->expectsOutput('Result: 2')
            ->assertExitCode(0);
    }

    public function testDivision()
    {
        $this->artisan('calculator')
            ->expectsQuestion('Enter the expression:', '2/2')
            ->expectsOutput('Result: 1')
            ->assertExitCode(0);
    }

    public function testComplexExpression()
    {
        $this->artisan('calculator')
            ->expectsQuestion('Enter the expression:', '1+2*3')
            ->expectsOutput('Result: 7')
            ->assertExitCode(0);
    }

    public function testParenthesesExpression()
    {
        $this->artisan('calculator')
            ->expectsQuestion('Enter the expression:', '(1+2)*3')
            ->expectsOutput('Result: 9')
            ->assertExitCode(0);
    }
}
