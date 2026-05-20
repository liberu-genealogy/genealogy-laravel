# Transcription Feature Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                         USER INTERFACE                              │
│  /transcriptions route → DocumentTranscriptionComponent (Livewire)  │
└────────────────────┬────────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      LIVEWIRE COMPONENT                             │
│  • File upload with validation                                      │
│  • Document selection                                               │
│  • Transcription display                                            │
│  • Edit/correction interface                                        │
│  • Statistics dashboard                                             │
└────────────────────┬────────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      SERVICE LAYER                                  │
│              HandwritingRecognitionService                          │
│                                                                     │
│  processDocument()          → Upload & process new documents        │
│  performOCR()              → Google Vision API / Fallback          │
│  applyCorrection()         → Save user corrections                 │
│  learnFromCorrection()     → Track patterns for ML                 │
│  getTeamStats()            → Optimized statistics query            │
└────────┬────────────────────────────────────────────┬───────────────┘
         │                                            │
         ▼                                            ▼
┌──────────────────────┐                    ┌──────────────────────┐
│   EXTERNAL APIs      │                    │    FILE STORAGE     │
│                      │                    │                      │
│  Google Cloud Vision │                    │  Laravel Storage     │
│  API (OCR)          │                    │  (public disk)       │
└──────────────────────┘                    └──────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      MODEL LAYER                                    │
│                                                                     │
│  DocumentTranscription                 TranscriptionCorrection      │
│  ├─ id                                ├─ id                        │
│  ├─ team_id (FK)                      ├─ document_transcription_id │
│  ├─ user_id (FK)                      ├─ user_id (FK)             │
│  ├─ original_filename                 ├─ original_text             │
│  ├─ document_path                     ├─ corrected_text            │
│  ├─ raw_transcription                 ├─ position_start            │
│  ├─ corrected_transcription           ├─ position_end              │
│  ├─ metadata (JSON)                   ├─ correction_metadata       │
│  ├─ status                            └─ timestamps                │
│  ├─ processed_at                                                   │
│  └─ timestamps + soft deletes                                      │
└─────────────────────────────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      DATABASE LAYER                                 │
│                                                                     │
│  document_transcriptions table    transcription_corrections table   │
│  ├─ Indexes on team_id           ├─ Indexes on document_id        │
│  ├─ Indexes on user_id           ├─ Indexes on user_id            │
│  ├─ Indexes on status            └─ Foreign key constraints        │
│  └─ Foreign key constraints                                        │
└─────────────────────────────────────────────────────────────────────┘
```

## Data Flow

### 1. Upload Flow
```
User uploads image 
    → Livewire validates file
    → Service stores to disk
    → Service calls Google Vision API
    → Service saves transcription to DB
    → UI updates with result
```

### 2. Correction Flow
```
User selects transcription
    → UI shows document + text side-by-side
    → User edits text
    → Service saves correction to DB
    → Service updates transcription
    → Service logs pattern for learning
    → UI shows success message
```

### 3. Statistics Flow
```
UI requests stats
    → Service executes single optimized query
    → Returns aggregated data:
        • Total transcriptions
        • Completed count
        • Pending count
        • Failed count
        • Average confidence
        • Total corrections
    → UI displays in dashboard cards
```

## Key Design Decisions

### 1. Team-Scoped Access
- All transcriptions belong to a team
- Users only see their team's documents
- Enforced at service and component level

### 2. Dual Transcription Storage
- `raw_transcription`: Original AI output
- `corrected_transcription`: User-edited version
- Allows tracking improvements and reverting if needed

### 3. Metadata as JSON
- Flexible storage for AI confidence scores
- Processing time tracking
- Language detection
- Future extensibility without migrations

### 4. Learning System
- Every correction tracked separately
- Position information stored
- Metadata for pattern analysis
- Foundation for future ML training

### 5. Performance Optimization
- Single SQL query for all statistics
- Proper database indexing
- Conditional aggregation in SQL
- JSON field extraction optimization

### 6. Graceful Degradation
- Fallback OCR when API unavailable
- Clear error messaging
- Pending/processing/failed states
- Soft deletes for data recovery

## Testing Strategy

### Unit Tests (8 tests)
- Service method validation
- Model helper methods
- Statistics calculation
- Correction application

### Feature Tests (11 tests)
- Component lifecycle
- File upload validation
- User interactions
- Team isolation
- Statistics display

### Integration Points
- Google Cloud Vision API (mocked in tests)
- File storage (faked in tests)
- Database transactions
- Livewire interactions

## Security Measures

1. **Authentication**: Required for all routes
2. **Authorization**: Team-based access control
3. **Validation**: File type and size limits
4. **Sanitization**: SQL injection prevention
5. **Encryption**: API keys in environment
6. **Audit Trail**: User tracking on all operations

## Scalability Considerations

- Queue-ready architecture (async processing)
- Batch processing capability
- Indexed database tables
- Optimized queries
- Cloud storage support
- Horizontal scaling ready
