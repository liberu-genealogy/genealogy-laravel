<?php

namespace App\Services;

use LaravelGrampsXml\Services\XmlReader;
use LaravelGrampsXml\Services\XmlWriter;
use Illuminate\Support\Collection;

class GrampsXmlService
{
    /**
     * Generate GrampsXML content from people and families data
     *
     * @param Collection $people
     * @param Collection $families
     * @return string
     */
    public function generateGrampsXmlContent(Collection $people, Collection $families): string
    {
        $writer = new XmlWriter();
        
        $data = [
            'people' => $this->convertPeople($people),
            'families' => $this->convertFamilies($families),
        ];
        
        return $writer->createGrampsXml($data);
    }

    /**
     * Convert Person models to GrampsXML format
     *
     * @param Collection $people
     * @return array
     */
    private function convertPeople(Collection $people): array
    {
        return $people->map(function ($person) {
            $personData = [
                'handle' => 'person_' . $person->id,
                'id' => 'I' . str_pad((string)$person->id, 4, '0', STR_PAD_LEFT),
                'change' => $person->updated_at ? $person->updated_at->timestamp : time(),
                'gender' => $this->mapGender($person->sex ?? 'U'),
            ];

            // Add name if available
            if ($person->givn || $person->surn) {
                $personData['name'] = [
                    'type' => 'Birth Name',
                    'first' => $person->givn ?? '',
                    'surname' => $person->surn ?? '',
                ];
            }

            return $personData;
        })->toArray();
    }

    /**
     * Convert Family models to GrampsXML format
     *
     * @param Collection $families
     * @return array
     */
    private function convertFamilies(Collection $families): array
    {
        return $families->map(function ($family) {
            $familyData = [
                'handle' => 'family_' . $family->id,
                'id' => 'F' . str_pad((string)$family->id, 4, '0', STR_PAD_LEFT),
                'change' => $family->updated_at ? $family->updated_at->timestamp : time(),
            ];

            // Add father reference
            if ($family->husband_id) {
                $familyData['father'] = 'person_' . $family->husband_id;
            }

            // Add mother reference
            if ($family->wife_id) {
                $familyData['mother'] = 'person_' . $family->wife_id;
            }

            return $familyData;
        })->toArray();
    }

    /**
     * Map database gender to GrampsXML gender format
     *
     * @param string $sex
     * @return string
     */
    private function mapGender(string $sex): string
    {
        return match(strtoupper($sex)) {
            'M' => 'M',
            'F' => 'F',
            default => 'U',
        };
    }

    /**
     * Parse GrampsXML file and return structured data
     *
     * @param string $filePath
     * @return array
     * @throws \Exception
     */
    public function parseGrampsXml(string $filePath): array
    {
        $reader = new XmlReader();
        return $reader->import($filePath);
    }
}
