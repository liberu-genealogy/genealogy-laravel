# Facial Recognition Feature Documentation

## Overview

The facial recognition feature helps users organize and tag family members in uploaded photos automatically. The system can detect faces, suggest person tags, and allow users to review and confirm these suggestions.

## User Guide

### Uploading Photos

1. Navigate to a Person's profile in the application
2. Click on the "Photos" tab
3. Click "New Photo" or "Upload Photo"
4. Select an image file from your device
5. Add an optional description
6. Click "Save"

The system will automatically analyze the photo for faces upon upload.

### Reviewing Facial Recognition Tags

1. Navigate to **Family Tree > Review Photo Tags** in the main menu
2. You'll see photos with detected faces that need review
3. For each detected face:
   - The system shows the suggested person (if a match was found)
   - Displays the confidence score
   - Shows the face location with a bounding box overlay
   
4. Review options:
   - **Confirm**: Accept the suggested tag
   - **Select Different Person**: Choose the correct person from the dropdown
   - **Reject**: Mark the tag as incorrect
   - **Skip**: Move to the next tag without making a decision

5. The progress bar shows how many tags remain to review

### Managing Photos

From a Person's profile Photos tab, you can:

- **View all photos** associated with that person
- **See tag counts** for each photo
- **Manually analyze** photos that weren't automatically analyzed
- **Edit photo descriptions**
- **Delete photos** (and their associated tags)

### Understanding Confidence Scores

- **90-100%**: Very high confidence - likely accurate
- **80-89%**: High confidence - usually accurate
- **70-79%**: Medium confidence - review carefully
- **Below 70%**: Low confidence - likely needs correction

## Features

### Automatic Face Detection

- Detects multiple faces in a single photo
- Provides bounding box coordinates for each face
- Calculates confidence scores for matches

### Person Matching

- Compares detected faces with known face encodings
- Suggests person tags based on similarity
- Learns from confirmed tags to improve future matches

### Tag Management

- Three status states: Pending, Confirmed, Rejected
- Track who confirmed each tag and when
- Update person assignments as needed

### Face Encoding

- Stores facial features for confirmed tags
- Uses encodings for future matching
- Supports multiple encodings per person

## Developer Guide

### Architecture

The facial recognition system is built with a provider pattern, allowing different facial recognition services to be used:

```
FacialRecognitionService (Main Service)
    ↓
FacialRecognitionProviderInterface
    ↓
├── MockProvider (Development/Testing)
├── AwsRekognitionProvider (Production - Not yet implemented)
└── AzureFaceApiProvider (Future option - Not yet implemented)
```

### Adding a New Provider

To add a new facial recognition provider (e.g., AWS Rekognition):

1. Create a new provider class implementing `FacialRecognitionProviderInterface`:

```php
<?php

namespace App\Services\FacialRecognition\Providers;

use App\Services\FacialRecognition\FacialRecognitionProviderInterface;

class AwsRekognitionProvider implements FacialRecognitionProviderInterface
{
    public function detectFaces(string $imagePath): array
    {
        // Implement AWS Rekognition face detection
    }

    public function matchFaces(string $imagePath, array $faceEncodings): array
    {
        // Implement AWS Rekognition face matching
    }

    public function getFaceEncoding(string $imagePath, array $boundingBox): string
    {
        // Implement AWS Rekognition face encoding
    }

    public function isAvailable(): bool
    {
        // Check if AWS credentials are configured
    }
}
```

2. Update `FacialRecognitionService::getProvider()` to include your new provider:

```php
protected function getProvider(): FacialRecognitionProviderInterface
{
    $provider = config('services.facial_recognition.provider', 'mock');

    return match ($provider) {
        'mock' => new MockProvider(),
        'aws' => new AwsRekognitionProvider(),
        // Add your provider here
        default => new MockProvider(),
    };
}
```

3. Add configuration in `config/services.php`:

```php
'facial_recognition' => [
    'provider' => env('FACIAL_RECOGNITION_PROVIDER', 'mock'),
    'aws' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
],
```

4. Add environment variables to `.env`:

```env
FACIAL_RECOGNITION_PROVIDER=aws
AWS_ACCESS_KEY_ID=your_key_here
AWS_SECRET_ACCESS_KEY=your_secret_here
AWS_DEFAULT_REGION=us-east-1
```

### Database Schema

#### person_photos
Stores photo metadata for person photos.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| person_id | bigint | Foreign key to people table |
| team_id | bigint | Foreign key to teams table |
| file_path | string | Storage path to photo file |
| file_name | string | Original filename |
| mime_type | string | File MIME type |
| file_size | integer | File size in bytes |
| width | integer | Image width in pixels |
| height | integer | Image height in pixels |
| description | text | Optional photo description |
| is_analyzed | boolean | Whether facial recognition has been run |
| analyzed_at | timestamp | When facial recognition was run |

#### photo_tags
Stores facial recognition tags linking faces to people.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| photo_id | bigint | Foreign key to person_photos |
| person_id | bigint | Foreign key to people (nullable) |
| team_id | bigint | Foreign key to teams |
| confidence | decimal(5,2) | Match confidence score (0-100) |
| bounding_box | json | Face coordinates {left, top, width, height} |
| status | enum | Tag status: pending, confirmed, rejected |
| confirmed_by | bigint | Foreign key to users (who confirmed) |
| confirmed_at | timestamp | When tag was confirmed |

#### face_encodings
Stores face encoding data for matching.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| person_id | bigint | Foreign key to people |
| team_id | bigint | Foreign key to teams |
| source_photo_id | bigint | Foreign key to person_photos |
| encoding | text | Encrypted face encoding data |
| provider | string | Provider that created encoding |

### API Methods

#### FacialRecognitionService

**analyzePhoto(PersonPhoto $photo): array**
- Analyzes a photo for faces
- Creates tags for detected faces
- Returns results with face count and success status

**confirmTag(PhotoTag $tag, int $userId, bool $createEncoding = true): bool**
- Confirms a photo tag
- Optionally creates face encoding
- Records who confirmed and when

**rejectTag(PhotoTag $tag): bool**
- Rejects a photo tag
- Updates status to rejected

**updateTagPerson(PhotoTag $tag, int $personId, int $userId): bool**
- Updates tag to different person
- Confirms the corrected tag
- Creates face encoding for new assignment

**getPendingTags(?int $teamId = null, int $limit = 50)**
- Returns pending tags for review
- Filtered by team if specified
- Ordered by creation date

### Testing

Run the test suite:

```bash
php artisan test --filter FacialRecognition
php artisan test --filter PhotoTagging
```

Unit tests cover:
- Face detection
- Tag creation and management
- Person matching
- Error handling

Feature tests cover:
- Complete photo upload workflow
- Tag review and confirmation
- Tag correction and rejection
- Relationship integrity

### Security Considerations

1. **Face Encodings**: Stored encrypted in the database
2. **File Storage**: Photos stored in Laravel's storage with proper permissions
3. **Team Isolation**: All queries filtered by team_id for multi-tenancy
4. **User Authorization**: Only team members can review and confirm tags
5. **Input Validation**: File uploads validated for type and size

## Configuration

### Environment Variables

```env
# Facial Recognition Provider
FACIAL_RECOGNITION_PROVIDER=mock  # Options: mock, aws

# AWS Rekognition (if using AWS provider)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_REKOGNITION_COLLECTION_ID=genealogy-faces
```

### Storage Configuration

Photos are stored using Laravel's filesystem in the `public` disk under `person-photos/` directory.

To publish storage:
```bash
php artisan storage:link
```

## Troubleshooting

### Photos Not Being Analyzed

1. Check if the storage link is created: `php artisan storage:link`
2. Verify file permissions on storage directory
3. Check logs for errors: `tail -f storage/logs/laravel.log`
4. Ensure the provider is configured correctly

### No Faces Detected

1. Verify image quality (should be at least 400x400 pixels)
2. Check if faces are clearly visible and front-facing
3. Try re-analyzing the photo manually
4. Review provider-specific limitations

### Tags Not Matching Correctly

1. Ensure confirmed tags exist for the person
2. Check if face encodings were created
3. Verify the person has multiple confirmed tags for better matching
4. Review confidence threshold in provider

## Future Enhancements

- [ ] Implement AWS Rekognition provider
- [ ] Add batch photo upload
- [ ] Implement automatic tag confirmation above threshold
- [ ] Add face grouping for unknown faces
- [ ] Support for video analysis
- [ ] Mobile-optimized review interface
- [ ] Export tagged photo collections
- [ ] Integration with photo timeline view

## Support

For issues or questions:
- Check the logs: `storage/logs/laravel.log`
- Review the test suite for usage examples
- Consult the codebase documentation
- Contact the development team
