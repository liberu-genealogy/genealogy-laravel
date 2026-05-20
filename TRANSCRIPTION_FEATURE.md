# Handwritten Document Transcription Feature

## Overview

This feature allows users to upload handwritten historical documents and uses AI-powered OCR (Optical Character Recognition) to transcribe them. Users can then review, edit, and correct the transcriptions, and the system learns from these corrections to improve accuracy over time.

## Features

- **Document Upload**: Upload images of handwritten documents (JPG, PNG, etc.)
- **AI Transcription**: Automatic transcription using Google Cloud Vision API
- **User Corrections**: Easy-to-use interface for reviewing and correcting transcriptions
- **Learning System**: Tracks user corrections to improve future transcriptions
- **Multi-Team Support**: Transcriptions are team-scoped for proper access control
- **Statistics Dashboard**: View transcription stats including accuracy and completion rates

## Setup

### 1. Google Cloud Vision API Configuration

To enable handwriting recognition, you need to configure the Google Cloud Vision API:

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the "Cloud Vision API"
4. Create credentials (API Key)
5. Add the API key to your `.env` file:

```env
GOOGLE_VISION_API_KEY=your_api_key_here
```

### 2. Storage Configuration

Ensure your `config/filesystems.php` has the public disk configured:

```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

Run the storage link command:

```bash
php artisan storage:link
```

### 3. Database Migration

Run the migrations to create the required tables:

```bash
php artisan migrate
```

This will create:
- `document_transcriptions` - Stores uploaded documents and transcriptions
- `transcription_corrections` - Tracks user corrections for learning

## Usage

### Accessing the Feature

Navigate to `/transcriptions` while logged in to access the transcription interface.

### Uploading a Document

1. Click "Choose an image file" to select a handwritten document
2. Preview the image to ensure it uploaded correctly
3. Click "Upload & Transcribe" to start the transcription process
4. The AI will process the document and provide an initial transcription

### Reviewing and Correcting Transcriptions

1. Select a transcription from the list on the left
2. View the original document image and transcription side-by-side
3. Click "Edit" to start making corrections
4. Make your changes in the text editor
5. Click "Save Correction" to save your changes

The system will:
- Save your corrected version
- Track the correction for future learning
- Update the transcription immediately

### Understanding Statistics

The dashboard shows:
- **Total Transcriptions**: All documents uploaded by your team
- **Completed**: Successfully transcribed documents
- **Total Corrections**: Number of user corrections made
- **Avg. Confidence**: Average AI confidence score (0-100%)

## API Integration

### Without Google Cloud Vision API

If you don't configure a Google Vision API key, the system will use a fallback mode that provides placeholder transcriptions. This is useful for:
- Development and testing
- Demonstrations
- Environments where the API is not available

### Alternative OCR Services

You can extend the `HandwritingRecognitionService` to support other OCR services:

1. Open `app/Services/HandwritingRecognitionService.php`
2. Add a new method like `performAzureOCR()` or `performAWSTextract()`
3. Update the `performOCR()` method to call your new service

## Architecture

### Models

- **DocumentTranscription**: Represents an uploaded document and its transcription
  - `team_id`: Links to the team that owns the document
  - `user_id`: User who uploaded the document
  - `document_path`: Path to the stored image
  - `raw_transcription`: Initial AI transcription
  - `corrected_transcription`: User-corrected version
  - `metadata`: Additional data (confidence scores, processing time, etc.)
  - `status`: Processing status (pending, processing, completed, failed)

- **TranscriptionCorrection**: Tracks individual corrections
  - `document_transcription_id`: Links to the transcription
  - `user_id`: User who made the correction
  - `original_text`: Text before correction
  - `corrected_text`: Text after correction
  - `correction_metadata`: Additional context for learning

### Service Layer

**HandwritingRecognitionService** provides:
- `processDocument()`: Handles document upload and OCR
- `performOCR()`: Orchestrates OCR with different providers
- `applyCorrection()`: Saves user corrections
- `learnFromCorrection()`: Implements learning logic
- `getTeamStats()`: Calculates team statistics

### Livewire Component

**DocumentTranscriptionComponent** handles:
- Document upload with real-time preview
- Transcription list management
- Editing interface
- Real-time updates

## Testing

Run the test suite:

```bash
php artisan test --filter=Transcription
```

Tests cover:
- Document upload and processing
- OCR functionality
- User corrections
- Team isolation
- Statistics calculation
- Component interactions

## Security Considerations

- Files are validated (images only, max 10MB)
- Team-based access control
- Soft deletes for data recovery
- API keys stored securely in environment variables
- User authentication required for all operations

## Performance Tips

1. **Image Optimization**: Resize large images before upload to reduce processing time
2. **Batch Processing**: For many documents, consider implementing a queue system
3. **Caching**: API results can be cached to avoid redundant calls
4. **Background Jobs**: Move OCR processing to background jobs for large documents

## Troubleshooting

### "Upload failed" Error

- Check file size (max 10MB)
- Verify file is an image format
- Ensure storage permissions are correct

### Low Confidence Scores

- Ensure image is clear and high resolution
- Check lighting and contrast
- Try preprocessing images (enhance contrast, remove noise)

### API Errors

- Verify API key is correct
- Check API quota and billing
- Ensure network connectivity to Google Cloud

## Future Enhancements

Potential improvements:
- Support for multi-page documents
- Batch upload and processing
- Export transcriptions to various formats
- Advanced learning with custom ML models
- Integration with genealogy records
- Collaborative correction features
- Mobile app support

## Support

For issues or questions:
1. Check the logs: `storage/logs/laravel.log`
2. Review the test suite for usage examples
3. Consult the code comments in the service class
4. Open an issue on GitHub

## License

This feature is part of the Genealogy Laravel project and follows the same MIT license.
