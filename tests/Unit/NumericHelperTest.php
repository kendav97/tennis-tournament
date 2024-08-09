<?php

namespace Tests\Unit;

use App\Http\Helpers\NumericHelper;
use PHPUnit\Framework\TestCase;

class NumericHelperTest extends TestCase
{
    public function test_isPowerOfTwo_method(): void
    {
        $this->assertTrue(NumericHelper::isPowerOfTwo(0) == false);
        $this->assertTrue(NumericHelper::isPowerOfTwo(1) == true);
        $this->assertTrue(NumericHelper::isPowerOfTwo(2) == true);
        $this->assertTrue(NumericHelper::isPowerOfTwo(3) == false);
        $this->assertTrue(NumericHelper::isPowerOfTwo(4) == true);
        $this->assertTrue(NumericHelper::isPowerOfTwo(6) == false);
        $this->assertTrue(NumericHelper::isPowerOfTwo(8) == true);
        $this->assertTrue(NumericHelper::isPowerOfTwo(10) == false);
        $this->assertTrue(NumericHelper::isPowerOfTwo(12) == false);
        $this->assertTrue(NumericHelper::isPowerOfTwo(16) == true);
    }

    public function test_generatePowersOfTwo_method(): void
    {
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys(
            [2, 4, 8, 16, 32, 64],
            NumericHelper::generatePowersOfTwo(6),
            []
        );
    }
}
