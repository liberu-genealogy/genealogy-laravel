# Social Media Integration for Family Discovery

## Overview

This feature allows users to connect their social media accounts to discover and connect with potential family members on social platforms. The system uses surname matching and other genealogical data to identify potential relatives.

## Features

### 1. OAuth Integration
- Support for Facebook, Google, and Twitter
- Secure account connection via Socialstream
- Token management and refresh

### 2. Privacy Controls
Users have granular control over their privacy settings:
- **Allow Family Discovery**: Enable/disable being discoverable by potential relatives
- **Show Profile to Matches**: Control whether matches can see your profile
- **Share Family Tree with Matches**: Choose to share your genealogy data with accepted connections
- **Allow Contact from Matches**: Control whether potential matches can contact you

### 3. Family Matching
- Automatic matching based on common surnames in family trees
- Confidence scoring (0-100) based on matching criteria
- Support for pending, accepted, and rejected connections

### 4. Account Management
- Connect/disconnect social media accounts
- Enable/disable family matching per account
- Sync account data on-demand

## Database Schema

### Tables Created

1. **social_connection_privacy**
   - Stores user privacy preferences
   - One record per user
   - Controls visibility and sharing options

2. **social_family_connections**
   - Stores discovered family connections
   - Links users to potential relatives found on social media
   - Tracks connection status and matching criteria

3. **connected_accounts** (enhanced)
   - Added `enable_family_matching` field
   - Added `cached_profile_data` for storing synced profile information
   - Added `last_synced_at` timestamp

## Setup Instructions

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Configure OAuth Providers

Add the following to your `.env` file:

```env
# Facebook OAuth
FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI=https://yourdomain.com/oauth/facebook/callback

# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=https://yourdomain.com/oauth/google/callback

# Twitter OAuth
TWITTER_CLIENT_ID=your_twitter_api_key
TWITTER_CLIENT_SECRET=your_twitter_api_secret
TWITTER_REDIRECT_URI=https://yourdomain.com/oauth/twitter/callback
```

### 3. Register OAuth Applications

#### Facebook
1. Go to https://developers.facebook.com/apps
2. Create a new app or use existing
3. Add Facebook Login product
4. Configure Valid OAuth Redirect URIs
5. Copy App ID and App Secret to .env

#### Google
1. Go to https://console.cloud.google.com/
2. Create a new project or select existing
3. Enable Google+ API
4. Create OAuth 2.0 credentials
5. Add authorized redirect URIs
6. Copy Client ID and Client Secret to .env

#### Twitter
1. Go to https://developer.twitter.com/
2. Create a new app or use existing
3. Enable OAuth 2.0
4. Add callback URLs
5. Copy API Key and API Secret to .env

## Usage

### For End Users

1. **Connect Social Media Account**
   - Navigate to Social Connections page
   - Click on provider (Facebook, Google, Twitter)
   - Authorize the application

2. **Configure Privacy Settings**
   - Check/uncheck privacy options as desired
   - Click "Save Privacy Settings"

3. **Enable Family Matching**
   - Toggle "Family Matching" for connected accounts
   - Click "Sync" to update profile data
   - Click "Find New Matches" to discover connections

4. **Manage Connections**
   - Review pending connections with confidence scores
   - Accept or reject each connection
   - View accepted connections

### For Developers

#### Using the Services

**SocialMediaConnectionService**

```php
use App\Services\SocialMediaConnectionService;

$service = app(SocialMediaConnectionService::class);

// Enable family matching
$service->enableFamilyMatching($connectedAccount);

// Update privacy settings
$service->updatePrivacySettings($user, [
    'allow_family_discovery' => true,
    'share_tree_with_matches' => false,
]);

// Sync account data
$service->syncAccountData($connectedAccount);
```

**FamilyMatchingService**

```php
use App\Services\FamilyMatchingService;

$service = app(FamilyMatchingService::class);

// Find potential connections
$matches = $service->findPotentialConnections($user);

// Process and create connections
$count = $service->processMatches($user);
```

#### Livewire Component

Add to any Blade template:

```blade
@livewire('social-connections')
```

## Matching Algorithm

The family matching algorithm works as follows:

1. **Data Collection**
   - Extract unique surnames from user's family tree
   - Sync social media profile data

2. **Matching**
   - Find other users with the same social media provider
   - Compare surname lists
   - Calculate confidence score (20 points per common surname, max 100)

3. **Privacy Filtering**
   - Only match users who have enabled family discovery
   - Respect blocking and privacy settings

4. **Connection Creation**
   - Create pending connections for matches
   - Store matching criteria (common surnames)
   - Allow users to accept or reject

## Security Considerations

1. **OAuth Tokens**
   - Tokens are encrypted at rest
   - Refresh tokens are automatically managed
   - Expired tokens are handled gracefully

2. **Privacy**
   - Users must explicitly enable family discovery
   - Granular controls for data sharing
   - User blocking functionality

3. **Data Storage**
   - Profile data is cached, not stored permanently
   - Sync frequency limited to every 24 hours
   - Users can disconnect accounts and delete all data

## API Endpoints

The feature integrates with Socialstream's existing routes:

- `GET /oauth/{provider}` - Initiate OAuth flow
- `GET /oauth/{provider}/callback` - OAuth callback
- Additional routes handled by Livewire component

## Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run only social media tests
php artisan test --filter=Social
```

### Test Coverage

- `SocialMediaConnectionServiceTest` - Tests for connection management
- `FamilyMatchingServiceTest` - Tests for matching algorithm

## Troubleshooting

### Common Issues

1. **OAuth Redirect Mismatch**
   - Ensure redirect URIs match exactly in provider settings and .env
   - Check for http vs https

2. **No Matches Found**
   - Verify users have data in their family trees
   - Check privacy settings are enabled
   - Ensure family matching is enabled on accounts

3. **Sync Failures**
   - Check OAuth token validity
   - Review API rate limits
   - Check application logs

## Future Enhancements

Potential improvements:

1. **Enhanced Matching**
   - Use location data for matching
   - Incorporate birth/death dates
   - DNA matching integration

2. **Notifications**
   - Email notifications for new matches
   - In-app notifications

3. **Messaging**
   - Direct messaging between matches
   - Shared family tree collaboration

4. **Additional Providers**
   - LinkedIn integration
   - Instagram integration
   - Ancestry.com integration

## Support

For issues or questions:
- GitHub Issues: https://github.com/liberu-genealogy/genealogy-laravel/issues
- Documentation: https://github.com/liberu-genealogy/genealogy-laravel
