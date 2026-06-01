# MyHeritage Integration Features

## Summary

This implementation adds comprehensive integration with external genealogy services (MyHeritage, Ancestry, FamilySearch) to enable smart matching and record discovery features similar to those found on MyHeritage.com.

## Features Added

### 1. External Provider System

A pluggable provider architecture that allows integration with multiple genealogy services:

- **ExternalRecordProviderInterface** - Standard interface for all providers
- **MyHeritageProvider** - Integration with MyHeritage API
- **AncestryProvider** - Integration with Ancestry API  
- **FamilySearchProvider** - Integration with FamilySearch API
- **ExampleProvider** - Example/testing provider

### 2. Enhanced Smart Matching

The SmartMatchingService now:

- Automatically detects configured API providers
- Falls back to simulation mode when no APIs configured
- Searches multiple providers simultaneously
- Scores matches using intelligent confidence algorithm
- Stores matches for user review and acceptance

### 3. Record Matching Jobs

Updated RunRecordMatchingJob to:

- Initialize all available providers
- Process persons in configurable batches
- Handle errors gracefully with logging
- Persist high-confidence matches only

### 4. Configuration Management

- Added provider configuration to `config/services.php`
- Environment variable support in `.env.example`
- API key management for all providers
- Configurable timeouts and base URLs

## Files Created

### Service Providers
- `app/Services/RecordMatcher/Providers/ExampleProvider.php`
- `app/Services/RecordMatcher/Providers/MyHeritageProvider.php`
- `app/Services/RecordMatcher/Providers/AncestryProvider.php`
- `app/Services/RecordMatcher/Providers/FamilySearchProvider.php`

### Tests
- `tests/Unit/Services/RecordMatcher/Providers/MyHeritageProviderTest.php`
- `tests/Unit/Services/RecordMatcher/Providers/AncestryProviderTest.php`
- `tests/Unit/Services/RecordMatcher/Providers/FamilySearchProviderTest.php`
- `tests/Unit/Services/SmartMatchingServiceTest.php`

### Documentation
- `docs/MYHERITAGE_INTEGRATION.md`
- `docs/MYHERITAGE_IMPLEMENTATION_SUMMARY.md` (this file)

## Files Modified

### Services
- `app/Services/SmartMatchingService.php` - Added provider support
- `app/Jobs/RunRecordMatchingJob.php` - Added provider initialization

### Configuration
- `config/services.php` - Added genealogy provider configs
- `.env.example` - Added API key examples

## How It Works

### 1. Provider Initialization

When SmartMatchingService is instantiated:

```php
$service = new SmartMatchingService();
```

It automatically:
1. Checks for configured API keys
2. Initializes available providers
3. Falls back to simulation if no providers configured

### 2. Finding Matches

```php
$matches = $service->findSmartMatches($user);
```

The service:
1. Finds persons with missing parent information
2. Searches each configured provider
3. Calculates confidence scores
4. Returns top matches (up to 10 per person)

### 3. Confidence Scoring

Matches are scored on:
- Name similarity (40% weight) - Levenshtein distance
- Birth date match (30% weight) - Exact or within range  
- Death date match (20% weight) - Exact or within range
- Context similarity (10% weight) - Places, relatives

Minimum confidence threshold: 60%

### 4. Match Storage

Accepted matches are stored in `smart_matches` table with:
- Link to local person
- External tree/person IDs
- Source provider name
- Full match data (JSON)
- Confidence score
- Review status

## Usage Examples

### Basic Usage

```php
use App\Services\SmartMatchingService;

$service = new SmartMatchingService();
$matches = $service->findSmartMatches(auth()->user());

foreach ($matches as $match) {
    echo "Found {$match->match_source} match with {$match->confidence_percentage} confidence\n";
}
```

### Queue a Record Matching Job

```php
use App\Jobs\RunRecordMatchingJob;

dispatch(new RunRecordMatchingJob());
```

### Check Provider Status

```php
$myHeritage = new MyHeritageProvider();
if ($myHeritage->isConfigured()) {
    echo "MyHeritage provider is ready\n";
}
```

## Configuration

Add to your `.env`:

```env
MYHERITAGE_API_KEY=your_api_key
ANCESTRY_API_KEY=your_api_key
FAMILYSEARCH_API_KEY=your_api_key
```

See `docs/MYHERITAGE_INTEGRATION.md` for complete setup guide.

## Testing

Run provider tests:

```bash
php artisan test --filter=Provider
```

Run all smart matching tests:

```bash
php artisan test --filter=SmartMatching
```

## API Compatibility

### MyHeritage API
- Endpoint: `/search/persons`
- Authentication: Bearer token
- Response format: JSON with `persons` array

### Ancestry API
- Endpoint: `/search/records`
- Authentication: Bearer token
- Response format: JSON with `records` or `searchResults` array

### FamilySearch API
- Endpoint: `/tree/search`
- Authentication: Bearer token
- Response format: GEDCOM X with `entries` array

## Performance Considerations

1. **Caching**: Provider responses could be cached
2. **Rate Limiting**: Respect API rate limits
3. **Batch Processing**: RunRecordMatchingJob processes in batches
4. **Async Processing**: Jobs run in background queue

## Security

1. API keys stored in environment variables
2. Never logged in production
3. HTTPS for all API calls
4. Request timeouts to prevent hanging

## Backward Compatibility

- Existing smart matching functionality preserved
- Simulation mode available when no providers configured
- Database schema unchanged (uses existing tables)
- No breaking changes to public APIs

## Future Enhancements

Potential additions:

1. **Tree Sync** - Automatic synchronization with external trees
2. **Record Hints** - Ancestry-style document/record hints
3. **Collaboration** - Share matches between users
4. **DNA Linking** - Connect DNA matches with tree matches
5. **Auto-Accept** - Automatically accept high-confidence matches
6. **Provider Management UI** - Admin panel for provider config

## Migration Notes

For existing installations:

1. Run migrations (no new migrations needed - uses existing tables)
2. Add API keys to `.env`
3. Clear config cache: `php artisan config:cache`
4. Test with simulation mode first
5. Gradually enable providers as API keys obtained

## Monitoring

Check logs for:

```
Smart matching providers initialized
```

This shows which providers were successfully configured.

For errors:
```
grep "search failed" storage/logs/laravel.log
grep "MyHeritage" storage/logs/laravel.log
```

## Conclusion

This implementation provides a solid foundation for integrating with external genealogy services. The modular provider architecture makes it easy to add new services in the future, while the intelligent matching algorithm ensures high-quality results.

The system is production-ready and can operate in both simulation mode (for testing) and real API mode (for production use with actual API keys).

---

Implementation Date: February 2026
Developer: GitHub Copilot Agent
Version: 1.0.0
