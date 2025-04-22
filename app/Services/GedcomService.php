<?php

namespace App\Services;

use FamilyTree365\LaravelGedcom\Utils\GedcomGenerator;

class GedcomService
{
    public function generateGedcomContent($people, $families): string
    {
        $writer = new GedcomGenerator($people, $families);

        return $writer->generate();
    }
}
