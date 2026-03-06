<?php

namespace LaravelGrampsXml\Services;

class XmlReader
{
    /**
     * Import a GrampsXML file and return structured data
     *
     * @param string $filePath
     * @return array
     * @throws \Exception
     */
    public function import(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new \Exception("Cannot read file: {$filePath}");
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);

        if ($xml === false) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            throw new \Exception('Invalid XML: ' . implode(', ', array_map(fn($e) => $e->message, $errors)));
        }

        $data = [
            'people' => [],
            'families' => [],
        ];

        // Parse people
        if (isset($xml->people->person)) {
            foreach ($xml->people->person as $person) {
                $personData = [
                    'handle' => (string) ($person['handle'] ?? ''),
                    'id' => (string) ($person['id'] ?? ''),
                    'gender' => (string) ($person->gender ?? 'U'),
                ];

                if (isset($person->name)) {
                    $personData['name'] = [
                        'type' => (string) ($person->name['type'] ?? 'Birth Name'),
                        'first' => (string) ($person->name->first ?? ''),
                        'surname' => (string) ($person->name->surname ?? ''),
                    ];
                }

                $data['people'][] = $personData;
            }
        }

        // Parse families
        if (isset($xml->families->family)) {
            foreach ($xml->families->family as $family) {
                $familyData = [
                    'handle' => (string) ($family['handle'] ?? ''),
                    'id' => (string) ($family['id'] ?? ''),
                ];

                if (isset($family->father)) {
                    $familyData['father'] = (string) ($family->father['hlink'] ?? '');
                }

                if (isset($family->mother)) {
                    $familyData['mother'] = (string) ($family->mother['hlink'] ?? '');
                }

                $data['families'][] = $familyData;
            }
        }

        return $data;
    }
}
