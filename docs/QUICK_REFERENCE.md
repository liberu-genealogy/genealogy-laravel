# Quick Reference: MyHeritage Integration

## For Developers

### Adding a New Provider

1. Create provider class in `app/Services/RecordMatcher/Providers/`:

```php
<?php

namespace App\Services\RecordMatcher\Providers;

use App\Models\Person;

class YourProvider implements ExternalRecordProviderInterface
{
    public function search($localPerson): array
    {
        // Implement search logic
        return [];
    }
    
    public function getName(): string
    {
        return 'YourProviderName';
    }
    
    public function isConfigured(): bool
    {
        return !empty(config('services.yourprovider.api_key'));
    }
}
```

2. Add configuration to `config/services.php`:

```php
'yourprovider' => [
    'api_key' => env('YOURPROVIDER_API_KEY'),
    'base_url' => env('YOURPROVIDER_BASE_URL', 'https://api.yourprovider.com'),
    'timeout' => env('YOURPROVIDER_TIMEOUT', 30),
],
```

3. Add environment variables to `.env.example`:

```env
YOURPROVIDER_API_KEY=
YOURPROVIDER_BASE_URL=https://api.yourprovider.com
YOURPROVIDER_TIMEOUT=30
```

4. Register in SmartMatchingService `initializeProviders()`:

```php
$yourProvider = new YourProvider();
if ($yourProvider->isConfigured()) {
    $this->providers['yourprovider'] = $yourProvider;
}
```

5. Create tests in `tests/Unit/Services/RecordMatcher/Providers/YourProviderTest.php`

### Testing Providers

```bash
# Test specific provider
php artisan test --filter=MyHeritageProviderTest

# Test all providers
php artisan test --filter=Provider

# Test smart matching service
php artisan test --filter=SmartMatchingServiceTest
```

### Debugging

Enable detailed logging:

```php
use Illuminate\Support\Facades\Log;

Log::info('Provider search', [
    'person_id' => $person->id,
    'provider' => $this->getName(),
    'results_count' => count($results),
]);
```

Check logs:

```bash
tail -f storage/logs/laravel.log | grep -i "provider\|matching"
```

### Common Issues

**Provider not initializing?**
- Check API key in `.env`
- Run `php artisan config:cache`
- Verify `isConfigured()` returns true

**No matches found?**
- Check person has required data (name, dates)
- Verify API endpoint is correct
- Check API key permissions
- Review confidence threshold (default 60%)

**API errors?**
- Check API key validity
- Verify API endpoint URL
- Check rate limits
- Review timeout settings

### Performance Tips

1. **Batch Processing**: Use jobs for large-scale matching
2. **Caching**: Cache provider responses when appropriate
3. **Rate Limiting**: Respect API rate limits
4. **Async Processing**: Run jobs in background queues

### API Response Formats

**Standard Format** (all providers should return):

```php
[
    'id' => 'external-id',
    'external_id' => 'external-id',
    'tree_id' => 'tree-123',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'birth_year' => 1880,
    'birth_date' => '1880-05-15',
    'birth_place' => 'London, England',
    'death_year' => 1950,
    'death_date' => '1950-10-20',
    'death_place' => 'Manchester, England',
    'gender' => 'M',
    'parents' => [...],
    'spouse' => [...],
    'children' => [...],
    'source_url' => 'https://...',
    'tree_name' => 'Family Tree',
    'tree_owner' => 'Jane Doe',
]
```

### Confidence Scoring

Customize weights in `RecordMatcherService`:

```php
$this->weights = [
    'first_name' => 1.0,  // 100%
    'last_name' => 1.0,   // 100%
    'birth_year' => 0.8,  // 80%
    'birth_place' => 0.6, // 60%
    'parents' => 0.9,     // 90%
];
```

### Queue Configuration

For background processing:

```bash
# Start queue worker
php artisan queue:work

# Run single job
php artisan queue:work --once

# Monitor queue
php artisan queue:monitor
```

Dispatch job:

```php
use App\Jobs\RunRecordMatchingJob;

dispatch(new RunRecordMatchingJob());
```

### Database Queries

**Find all pending matches:**

```php
$matches = SmartMatch::where('status', 'pending')
    ->where('confidence_score', '>=', 0.8)
    ->with('person', 'user')
    ->get();
```

**Get matches by provider:**

```php
$myHeritageMatches = SmartMatch::where('match_source', 'myheritage')
    ->orderBy('confidence_score', 'desc')
    ->get();
```

**User's accepted matches:**

```php
$acceptedMatches = SmartMatch::where('user_id', $userId)
    ->where('status', 'accepted')
    ->get();
```

### HTTP Client Mocking

For tests:

```php
use Illuminate\Support\Facades\Http;

Http::fake([
    'api.myheritage.test/*' => Http::response([
        'persons' => [
            ['id' => 'MH-001', 'first_name' => 'John'],
        ],
    ], 200),
]);
```

### Code Review Checklist

- [ ] Provider implements `ExternalRecordProviderInterface`
- [ ] Configuration added to `config/services.php`
- [ ] Environment variables added to `.env.example`
- [ ] Tests created and passing
- [ ] Error handling implemented
- [ ] Logging added for debugging
- [ ] Rate limiting considered
- [ ] Documentation updated

## For Users

### Setup

1. Obtain API keys from providers:
   - MyHeritage: Contact MyHeritage developer program
   - Ancestry: https://www.ancestry.com/developers
   - FamilySearch: https://www.familysearch.org/developers

2. Add to `.env`:

```env
MYHERITAGE_API_KEY=your_key_here
ANCESTRY_API_KEY=your_key_here
FAMILYSEARCH_API_KEY=your_key_here
```

3. Clear cache:

```bash
php artisan config:cache
```

### Using Smart Matches

1. Navigate to **Research & Analysis** → **Smart Matches**
2. Click **Find New Matches** button
3. Wait for search to complete
4. Review matches in table:
   - Green badge = High confidence (80%+)
   - Yellow badge = Medium confidence (60-80%)
   - Red badge = Low confidence (<60%)
5. Click **View** to see details
6. Click **Accept** or **Reject** for each match

### Best Practices

- Add complete data to persons (names, dates, places)
- Review matches carefully before accepting
- Use source citations to track match origins
- Regularly run background matching jobs
- Keep API keys secure and private

## Support

- Documentation: See `docs/MYHERITAGE_INTEGRATION.md`
- Issues: GitHub Issues
- Community: GitHub Discussions

---

Last Updated: February 2026
