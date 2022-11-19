<?php

namespace App;

class Receipt
{
    protected $scanner;

    public static $width = 65;

    public function __construct(Scanner $scanner)
    {
        $this->scanner = $scanner;
    }

    private function line()
    {
        $line = '';
        for ($i = 0; $i <= self::$width; $i++) {
            $line .= '-';
        }
        return $line;
    }

    private function spaced($heading, $value, $hasColour = false, $isCurrency = false)
    {
        $string = $heading;
        $headingLength = strlen($heading);

        if ($hasColour) {
            $colourSymbolsLength = 5;
            $headingLength -= $colourSymbolsLength * 2;
        }

        if ($isCurrency) {
            $headingLength -= 2;
        }

        for ($i = 0; $i <= self::$width - ($headingLength + strlen("$value")); $i++) {
            $string .= ' ';
        }

        $string .= $value;
        return $string;
    }

    private function spaces($text)
    {
        $spaces = '';

        for ($i = 0; $i <= (self::$width / 2) - (intval(strlen($text) / 2)); $i++) {
            $spaces .= ' ';
        }
        return $spaces . $text;
    }

    public function print()
    {
        $appliedSavings = count($this->scanner->getAllSavings());

        echo <<<EOT
        \n
        {$this->spaces('Thank you for shopping!')}
        {$this->line()}
        {$this->scanner->listScanned()}

        {$this->spaced('Total products:',$this->scanner->getTotalScanned())}
        {$this->spaced('Total discounts applied:',$appliedSavings)}
        {$this->spaced('Total before discounts:',$this->scanner->getTotal(), false, true)}
        {$this->spaced("\e[32mTotal savings:\e[39m",$this->scanner->getSavingsTotal(), true, true)}
        {$this->spaced("\e[91mTOTAL:\e[39m",$this->scanner->getTotalAfterDiscounts(), true, true)}
        {$this->line()}
        \n
        EOT;
    }
}
