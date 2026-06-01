# MyHeritage Integration Guide

## Overview

This document provides instructions for integrating MyHeritage and other genealogy service APIs (Ancestry, FamilySearch) into the Liberu Genealogy application for smart matching and record discovery.

## Features Implemented

### 1. External Provider Architecture

The system now supports pluggable external genealogy providers through the `ExternalRecordProviderInterface`:

- **MyHeritageProvider** - Searches MyHeritage family trees
- **AncestryProvider** - Searches Ancestry.com records
- **FamilySearchProvider** - Searches FamilySearch Family Tree

### 2. Smart Matching Service

The `SmartMatchingService` has been enhanced to:

- Automatically detect and use configured providers
- Fall back to simulation mode when no providers are configured
- Search multiple providers in parallel
- Score and rank matches by confidence
- Store matches in the database for user review

### 3. Record Matching Jobs

The `RunRecordMatchingJob` has been updated to:

- Initialize all configured providers
- Process persons in batches
- Log progress and errors
- Persist high-confidence matches

## Configuration

### Step 1: Obtain API Keys

#### MyHeritage API
1. Visit MyHeritage Developer Portal (when available)
2. Create an application
3. Copy your API key

#### Ancestry API
1. Visit Ancestry Developer Portal
2. Register your application
3. Obtain API credentials

#### FamilySearch API
1. Visit https://www.familysearch.org/developers/
2. Register as a developer
3. Create an application
4. Get your API key

### Step 2: Configure Environment Variables

Add the following to your `.env` file:

```env
# MyHeritage API Configuration
MYHERITAGE_API_KEY=your_myheritage_api_key_here
MYHERITAGE_BASE_URL=https://api.myheritage.com/v1
MYHERITAGE_TIMEOUT=30

# Ancestry API Configuration
ANCESTRY_API_KEY=your_ancestry_api_key_here
ANCESTRY_BASE_URL=https://api.ancestry.com/v1
ANCESTRY_TIMEOUT=30

# FamilySearch API Configuration
FAMILYSEARCH_API_KEY=your_familysearch_api_key_here
FAMILYSEARCH_BASE_URL=https://api.familysearch.org/platform
FAMILYSEARCH_TIMEOUT=30
```

### Step 3: Verify Configuration

Check that your providers are properly configured:

```php
use App\Services\SmartMatchingService;

$service = new SmartMatchingService();
// Check logs to see which providers were initialized
```

## Usage

### Finding Smart Matches

#### Via UI

1. Navigate to Research & Analysis > Smart Matches
2. Click "Find New Matches" button
3. Wait for the system to search configured providers
4. Review matches in the table
5. Accept or reject matches as needed

#### Via Code

```php
use App\Services\SmartMatchingService;
use App\Models\User;

$service = new SmartMatchingService();
$user = User::find(1);

// Find matches for user's unknown ancestors
$matches = $service->findSmartMatches($user);

// Returns Collection of SmartMatch models
foreach ($matches as $match) {
    echo "Found match from {$match->match_source} with confidence {$match->confidence_percentage}\n";
}
```

### Running Record Matching Job

Queue the job to run in background:

```php
use App\Jobs\RunRecordMatchingJob;

dispatch(new RunRecordMatchingJob());
```

Or run via artisan command:

```bash
php artisan queue:work
```

## Provider Details

### MyHeritageProvider

**Search Parameters:**
- First name
- Last name
- Birth year/date
- Birth place
- Death year/date
- Death place
- Gender

**Response Format:**
- Parses MyHeritage API responses
- Normalizes data into standard format
- Includes tree ID and person ID for linking

### AncestryProvider

**Search Parameters:**
- Given name (first name)
- Surname (last name)
- Birth year
- Birth location
- Death year
- Death location
- Gender

**Response Format:**
- Supports both `records` and `searchResults` response keys
- Maps Ancestry-specific field names to standard format

### FamilySearchProvider

**Search Parameters:**
- Given name
- Surname
- Birth year/date
- Birth place
- Death year/date
- Death place
- Gender (male/female)

**Response Format:**
- Parses GEDCOM X format
- Extracts person data from nested structure
- Handles FamilySearch-specific naming conventions

## Confidence Scoring

Matches are scored based on:

- **Name similarity (40%)** - Using Levenshtein distance
- **Birth date similarity (30%)** - Exact or within range
- **Death date similarity (20%)** - Exact or within range
- **Context similarity (10%)** - Places, family members

Confidence threshold: 60% minimum for storage

## Database Schema

### smart_matches Table

```sql
- id
- user_id (foreign key)
- person_id (foreign key)
- external_tree_id
- external_person_id
- match_source ('myheritage', 'ancestry', 'familysearch')
- match_data (JSON)
- confidence_score (0.00-100.00)
- status ('pending', 'reviewed', 'accepted', 'rejected')
- reviewed_at (timestamp)
- created_at
- updated_at
```

## Testing

The following test files verify the integration:

- `tests/Unit/Services/RecordMatcher/Providers/MyHeritageProviderTest.php`
- `tests/Unit/Services/RecordMatcher/Providers/AncestryProviderTest.php`
- `tests/Unit/Services/RecordMatcher/Providers/FamilySearchProviderTest.php`
- `tests/Unit/Services/SmartMatchingServiceTest.php`

Run tests with:

```bash
php artisan test --filter=Provider
```

## Troubleshooting

### No Matches Found

1. Check that API keys are correctly set in `.env`
2. Verify API keys are valid and not expired
3. Check logs for API errors: `storage/logs/laravel.log`
4. Ensure person has sufficient data (name, dates)

### API Rate Limits

If you hit rate limits:

1. Reduce batch size in `RunRecordMatchingJob`
2. Add delays between API calls
3. Contact provider for higher rate limits

### Provider Not Initializing

Check logs for:
```
Smart matching providers initialized
```

If a provider is missing from the list, verify:
1. API key is set in `.env`
2. Configuration is loaded: `php artisan config:cache`
3. No syntax errors in provider class

## Security Considerations

1. **API Keys**: Never commit API keys to version control
2. **Rate Limiting**: Implement rate limiting to avoid API abuse
3. **Data Privacy**: Respect user privacy settings when searching
4. **Logging**: Don't log sensitive API responses in production

## Future Enhancements

Planned features:

1. **Automatic Synchronization** - Keep matches updated
2. **Tree Import** - Import entire trees from providers
3. **Hints System** - Ancestry-style record hints
4. **Collaboration** - Share matches with other users
5. **DNA Integration** - Link DNA matches with tree matches

## Support

For issues or questions:

- GitHub Issues: https://github.com/liberu-genealogy/genealogy-laravel/issues
- Documentation: See repository README
- Community: Join our discussions

## API References

- **MyHeritage API**: (Contact MyHeritage for documentation)
- **Ancestry API**: https://www.ancestry.com/developers
- **FamilySearch API**: https://www.familysearch.org/developers/docs/api/

---

Last updated: February 2026
Version: 1.0.0
