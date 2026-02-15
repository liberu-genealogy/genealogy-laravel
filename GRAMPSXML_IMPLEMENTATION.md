# GrampsXML Import/Export Implementation

This document describes the implementation of GrampsXML format support in the genealogy application.

## Overview

The application now supports both GEDCOM and GrampsXML file formats for importing and exporting genealogical data. GrampsXML is the native file format used by the Gramps genealogy software.

## Features

### Import
- **Automatic Format Detection**: The system automatically detects whether an uploaded file is GEDCOM (.ged) or GrampsXML (.gramps, .xml) based on file extension
- **Background Processing**: Imports are processed asynchronously using Laravel queues
- **GrampsXML to GEDCOM Conversion**: GrampsXML files are converted to GEDCOM format internally and processed using the existing GEDCOM parser

### Export
- **GEDCOM Export**: Export your family tree data to GEDCOM format (.ged)
- **GrampsXML Export**: Export your family tree data to GrampsXML format (.gramps)
- **Both available from**: GedcomResource record actions and dedicated export pages

## Implementation Details

### Core Components

1. **GrampsXmlService** (`app/Services/GrampsXmlService.php`)
   - Handles conversion between application data models and GrampsXML format
   - Uses the `liberu/laravel-gramps-xml` library for XML generation and parsing

2. **ImportGrampsXml Job** (`app/Jobs/ImportGrampsXml.php`)
   - Processes GrampsXML file imports asynchronously
   - Converts GrampsXML to GEDCOM format
   - Leverages existing GEDCOM parser infrastructure

3. **ExportGrampsXml Job** (`app/Jobs/ExportGrampsXml.php`)
   - Generates GrampsXML files from database records
   - Exports Person and Family data

4. **GrampsXmlExportPage** (`app/Filament/App/Pages/GrampsXmlExportPage.php`)
   - Dedicated Filament page for GrampsXML export
   - Located in "Data Management" navigation group

5. **Enhanced GedcomResource** (`app/Filament/App/Resources/GedcomResource.php`)
   - Updated to accept both .ged and .gramps/.xml file uploads
   - Provides separate export actions for GEDCOM and GrampsXML formats

### External Library

The implementation uses the `liberu/laravel-gramps-xml` library from:
- GitHub: https://github.com/liberu-genealogy/laravel-gramps-xml
- Supports Gramps XML DTD version 1.7.2
- Provides XML reading, writing, and validation

## Usage

### Importing Files

1. Navigate to the Gedcom resource in the admin panel
2. Click "Create" to upload a new file
3. Upload either:
   - A GEDCOM file (.ged)
   - A GrampsXML file (.gramps or .xml)
4. The system automatically detects the format and processes accordingly

### Exporting Data

#### Method 1: From GedcomResource
1. Navigate to the Gedcom resource
2. Select a record
3. Choose either:
   - "Export GEDCOM" action
   - "Export GrampsXML" action

#### Method 2: From Export Pages
1. Navigate to "Data Management" → "GrampsXML Export"
2. Click "Generate GrampsXML"
3. File will be generated asynchronously and stored in your storage

## Data Mapping

### Person Fields
- ID: Generated as person_{id}
- Handle: person_{id}
- Names: Given name (givn) and Surname (surn)
- Gender: M (Male), F (Female), U (Unknown)

### Family Fields
- ID: Generated as family_{id}
- Handle: family_{id}
- Father: Reference to husband_id
- Mother: Reference to wife_id

## Technical Notes

### File Storage
- Imported files are stored in `storage/app/private/gedcom-form-imports/`
- Exported files are stored in tenant-specific storage
- Maximum upload size: 100MB

### Queues
- Import and export operations run asynchronously
- Uses Laravel's queue system
- Status can be tracked via ImportJob model

### Caching
- Application caches are cleared after import completion
- Ensures new records are visible immediately

## Testing

Unit tests are provided in `tests/Unit/Services/GrampsXmlServiceTest.php` covering:
- XML generation
- Person data conversion
- Family data conversion
- Gender mapping

## Future Enhancements

Potential improvements for future versions:
- Support for more GrampsXML elements (events, places, sources, citations)
- Direct GrampsXML import without GEDCOM conversion
- Bidirectional synchronization with Gramps
- Validation of GrampsXML files against DTD before import
- Progress tracking for large imports

## Troubleshooting

### Import Issues
- Verify file is valid GrampsXML or GEDCOM format
- Check file size doesn't exceed 100MB
- Review Laravel logs for detailed error messages
- Ensure queue workers are running

### Export Issues
- Check user has proper permissions
- Verify storage directory is writable
- Check queue workers are processing jobs
- Review logs for export job failures
