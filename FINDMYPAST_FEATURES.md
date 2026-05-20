# FindMyPast Integration Features

This document describes the FindMyPast-specific features that have been added to the Liberu Genealogy application.

## Overview

FindMyPast.co.uk is a leading UK-based genealogy website known for its extensive British and Irish historical records. This implementation adds support for FindMyPast's major record categories and specialized matching algorithms.

## Features Added

### 1. Record Type System

A comprehensive record type categorization system has been added to support different types of genealogical records:

- **Vital Records**: Birth, Marriage, Death Certificates
- **Census Records**: All UK census years (1841-1911) plus 1939 Register
- **Newspaper Archives**: Articles, Obituaries, Marriage/Birth Announcements
- **Parish Records**: Baptisms, Marriages, Burials
- **Electoral Registers**: Voter registration records
- **GRO Index**: General Register Office birth, marriage, and death indices
- **Military Records**: Service records, War Graves (WWI, WWII)
- **Probate Records**: Wills and Probate grants
- **Poor Law Records**: Workhouse and Poor Law Union records
- **Court Records**: Criminal and civil court proceedings
- **Land Records**: Property deeds and ownership documents

### 2. Database Schema

#### record_types Table
Stores metadata about different record types including:
- Name, slug, and category
- Description
- Metadata schema (JSON) for type-specific fields
- Display settings (icon, color, sort order)

#### Enhanced sources Table
- Added `record_type_id` foreign key
- Added `archive_metadata` JSON field for type-specific data

#### Enhanced smart_matches Table
- Added `record_type_id` foreign key
- Added `record_category` field
- Added `search_criteria` JSON field to track search parameters

### 3. FindMyPast Matching Provider

A specialized service (`FindMyPastMatchingProvider`) that implements record-type-specific search and matching logic:

#### Newspaper Archives
- Searches obituaries based on death dates
- Searches marriage announcements
- Includes publication name, date, page number
- Generates obituary extracts

#### Parish Records
- Searches baptism records (birth + 7-90 days typically)
- Searches burial records (death + 3-10 days typically)
- Includes parish name, church name, diocese
- Captures parent names and abode information

#### Census Records
- Searches all available UK census years (1841-1911)
- Calculates age at each census
- Includes residence, household, occupation information
- County and enumeration district details

#### Electoral Registers
- Searches voter registration records (1832+)
- Includes address, constituency, qualification
- Samples records every 5-10 years

#### GRO Index
- Searches General Register Office indices (1837+)
- Birth, Marriage, and Death indices
- Includes quarter, year, district, volume, page
- Mother's maiden name for births

#### Military Records
- WWI service records (for men born 1880-1900)
- WWII service records (for men born 1900-1927)
- Includes service number, regiment, rank, enlistment date

#### Probate Records
- Searches wills and probate grants
- Includes probate date, court, estate value

#### Poor Law Records
- Workhouse admission records
- Includes workhouse name, union, admission/discharge dates

### 4. Confidence Scoring

Record-type-specific confidence scoring algorithms:

- **Newspapers**: 60% base + 15% if death date available
- **Parish Records**: 70% base + 10% for pre-1900 records
- **Census Records**: 75% base + 15% if age calculation matches
- **GRO Index**: 85% base (official government records)
- **Other types**: 50-70% base depending on record quality

### 5. User Interface

#### Smart Matches Resource
Enhanced to display:
- Record type/category as colored badges
- Filter by record type category
- Shows type-specific information in match data

#### Record Types Management
New admin resource for managing record types:
- Create, edit, delete record types
- Configure metadata schemas
- Set display properties (icons, colors)
- Control active/inactive status

## Usage

### Finding Smart Matches

1. Navigate to "Smart Matches" in the Research & Analysis section
2. Click "Find New Matches" button
3. The system will search all available platforms including FindMyPast
4. FindMyPast matches will include specific record types:
   - Newspapers (obituaries, notices)
   - Parish records (baptisms, burials)
   - Census records (all available years)
   - Electoral registers
   - GRO indices
   - Military records
   - Probate records

### Filtering Results

Use the new "Record Type" filter to narrow results:
- Newspaper
- Parish Record
- Census
- Electoral Register
- GRO Index
- Military
- Probate
- Poor Law/Workhouse

### Viewing Match Details

Each FindMyPast match includes record-specific metadata:
- **Census**: Year, district, household info, occupation
- **Newspaper**: Publication, date, page, article type
- **Parish**: Parish name, church, baptism/burial dates
- **GRO**: Quarter, district, volume, page numbers
- **Military**: Regiment, rank, service number

## Technical Implementation

### Models
- `RecordType` - Manages record type definitions
- Enhanced `Source` with record type relationship
- Enhanced `SmartMatch` with record type and category

### Services
- `FindMyPastMatchingProvider` - Specialized FindMyPast search logic
- Enhanced `SmartMatchingService` - Integrates FindMyPast provider

### Seeders
- `RecordTypeSeeder` - Seeds 24 different record types covering all major FindMyPast categories

### Migrations
- `create_record_types_table` - Record type definitions
- `add_record_type_to_sources` - Links sources to record types
- `add_record_type_to_smart_matches` - Enhanced smart matching

## Future Enhancements

Potential additions:
1. Real FindMyPast API integration (currently simulated)
2. OCR for newspaper image transcription
3. Parish boundary mapping
4. Census enumeration district visualization
5. Electoral constituency historical tracking
6. Military unit histories and battle participation
7. Probate value inflation calculator

## References

- FindMyPast.co.uk: https://www.findmypast.co.uk
- FindMyPast Collections: Major UK/Irish genealogy records
- GRO Index: General Register Office vital records index
- Parish Records: Church of England baptism, marriage, burial records
- 1939 Register: National Registration Act wartime census substitute
