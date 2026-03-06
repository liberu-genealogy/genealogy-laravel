<?php

namespace LaravelGrampsXml\Services;

class XmlWriter
{
    /**
     * Create GrampsXML content from structured data
     *
     * @param array $data
     * @return string
     */
    public function createGrampsXml(array $data): string
    {
        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('database');
        $xml->writeAttribute('xmlns', 'http://gramps-project.org/xml/1.7.1/');

        // Write people
        if (!empty($data['people'])) {
            $xml->startElement('people');
            foreach ($data['people'] as $person) {
                $xml->startElement('person');
                if (isset($person['handle'])) {
                    $xml->writeAttribute('handle', $person['handle']);
                }
                if (isset($person['id'])) {
                    $xml->writeAttribute('id', $person['id']);
                }
                if (isset($person['gender'])) {
                    $xml->writeElement('gender', $person['gender']);
                }
                if (isset($person['name'])) {
                    $xml->startElement('name');
                    if (isset($person['name']['type'])) {
                        $xml->writeAttribute('type', $person['name']['type']);
                    }
                    if (isset($person['name']['first'])) {
                        $xml->writeElement('first', $person['name']['first']);
                    }
                    if (isset($person['name']['surname'])) {
                        $xml->startElement('surname');
                        $xml->text($person['name']['surname']);
                        $xml->endElement();
                    }
                    $xml->endElement();
                }
                $xml->endElement();
            }
            $xml->endElement();
        }

        // Write families
        if (!empty($data['families'])) {
            $xml->startElement('families');
            foreach ($data['families'] as $family) {
                $xml->startElement('family');
                if (isset($family['handle'])) {
                    $xml->writeAttribute('handle', $family['handle']);
                }
                if (isset($family['id'])) {
                    $xml->writeAttribute('id', $family['id']);
                }
                if (isset($family['father'])) {
                    $xml->startElement('father');
                    $xml->writeAttribute('hlink', $family['father']);
                    $xml->endElement();
                }
                if (isset($family['mother'])) {
                    $xml->startElement('mother');
                    $xml->writeAttribute('hlink', $family['mother']);
                    $xml->endElement();
                }
                $xml->endElement();
            }
            $xml->endElement();
        }

        $xml->endElement(); // database
        $xml->endDocument();

        return $xml->outputMemory();
    }
}
