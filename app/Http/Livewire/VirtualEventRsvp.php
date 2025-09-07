<?php

namespace App\Http\Livewire;

use App\Models\VirtualEvent;
use App\Models\VirtualEventAttendee;
use App\Models\Person;
use App\Services\VideoConferencingService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VirtualEventRsvp extends Component
{
    use WithPagination;

    public $event;
    public $attendee;
    public $showRsvpModal = false;
    public $showInviteModal = false;
    public $showJoinModal = false;

    // RSVP form properties
    public $rsvp_status = 'accepted';
    public $rsvp_notes = '';
    public $guest_name = '';
    public $guest_email = '';
    public $invite_person_id = '';

    // Invite form properties
    public $invite_emails = [];
    public $invite_message = '';

    // Filters and search
    public $statusFilter = 'all';
    public $search = '';
    public $showAttendeeList = false;

    protected $rules = [
        'rsvp_status' => 'required|in:accepted,declined,maybe',
        'rsvp_notes' => 'nullable|string|max:500',
        'guest_name' => 'nullable|string|max:255',
        'guest_email' => 'nullable|email|max:255',
    ];

    protected $inviteRules = [
        'invite_emails' => 'required|array|min:1',
        'invite_emails.*' => 'email',
        'invite_message' => 'nullable|string|max:1000',
    ];

    public function mount(VirtualEvent $event)
    {
        $this->event = $event;
        $this->attendee = $this->getUserAttendee();
        $this->loadDefaultInviteMessage();
    }

    public function render()
    {
        $attendees = $this->getFilteredAttendees();
        $persons = Person::orderBy('name')->get();
        $canJoin = $this->event->canJoin();
        $userCanRsvp = $this->canUserRsvp();

        return view('livewire.virtual-event-rsvp', [
            'attendees' => $attendees,
            'persons' => $persons,
            'canJoin' => $canJoin,
            'userCanRsvp' => $userCanRsvp,
            'eventStats' => $this->getEventStats(),
        ]);
    }

    public function getUserAttendee()
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->event->attendees()
            ->where('user_id', Auth::id())
            ->first();
    }

    public function canUserRsvp()
    {
        if (!Auth::check()) {
            return false;
        }

        if ($this->event->status !== 'published') {
            return false;
        }

        if ($this->event->start_time <= now()) {
            return false;
        }

        if (!$this->event->require_rsvp) {
            return true;
        }

        return !$this->attendee || $this->attendee->rsvp_status === 'pending';
    }

    public function getFilteredAttendees()
    {
        if (!$this->showAttendeeList) {
            return collect();
        }

        $query = $this->event->attendees()
            ->with(['user', 'person']);

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('person', function ($personQuery) {
                    $personQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('givn', 'like', '%' . $this->search . '%')
                               ->orWhere('surn', 'like', '%' . $this->search . '%');
                })
                ->orWhere('guest_name', 'like', '%' . $this->search . '%')
                ->orWhere('guest_email', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('rsvp_status', $this->statusFilter);
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    public function getEventStats()
    {
        return [
            'total_attendees' => $this->event->attendee_count,
            'accepted' => $this->event->accepted_count,
            'pending' => $this->event->pendingAttendees()->count(),
            'declined' => $this->event->attendees()->where('rsvp_status', 'declined')->count(),
            'maybe' => $this->event->attendees()->where('rsvp_status', 'maybe')->count(),
            'actual_attended' => $this->event->actual_attendee_count,
        ];
    }

    public function openRsvpModal()
    {
        if ($this->attendee) {
            $this->rsvp_status = $this->attendee->rsvp_status;
            $this->rsvp_notes = $this->attendee->rsvp_notes ?? '';
        } else {
            $this->rsvp_status = 'accepted';
            $this->rsvp_notes = '';
        }

        $this->showRsvpModal = true;
    }

    public function submitRsvp()
    {
        $this->validate();

        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to RSVP.');
            return;
        }

        if (!$this->canUserRsvp() && !$this->attendee) {
            session()->flash('error', 'RSVP is not available for this event.');
            return;
        }

        // Check if event is at capacity
        if ($this->rsvp_status === 'accepted' && $this->event->isAtCapacity() && !$this->attendee) {
            session()->flash('error', 'This event is at maximum capacity.');
            return;
        }

        if ($this->attendee) {
            // Update existing RSVP
            $this->attendee->update([
                'rsvp_status' => $this->rsvp_status,
                'rsvp_notes' => $this->rsvp_notes,
                'rsvp_date' => now(),
            ]);
        } else {
            // Create new RSVP
            $this->attendee = VirtualEventAttendee::create([
                'virtual_event_id' => $this->event->id,
                'user_id' => Auth::id(),
                'rsvp_status' => $this->rsvp_status,
                'rsvp_notes' => $this->rsvp_notes,
                'rsvp_date' => now(),
            ]);
        }

        $this->showRsvpModal = false;
        $this->resetRsvpForm();

        $statusMessage = match ($this->rsvp_status) {
            'accepted' => 'Thank you for accepting! We look forward to seeing you at the event.',
            'declined' => 'Thank you for letting us know. We hope you can join us next time.',
            'maybe' => 'Thank you for your response. Please let us know if your plans change.',
            default => 'Your RSVP has been recorded.',
        };

        session()->flash('success', $statusMessage);
        $this->dispatch('rsvp-updated');
    }

    public function openInviteModal()
    {
        $this->showInviteModal = true;
    }

    public function addInviteEmail()
    {
        $this->invite_emails[] = '';
    }

    public function removeInviteEmail($index)
    {
        unset($this->invite_emails[$index]);
        $this->invite_emails = array_values($this->invite_emails);
    }

    public function invitePerson()
    {
        if (!$this->invite_person_id) {
            return;
        }

        $person = Person::find($this->invite_person_id);
        if (!$person || !$person->email) {
            session()->flash('error', 'Selected person does not have an email address.');
            return;
        }

        // Check if person is already invited
        $existingAttendee = $this->event->attendees()
            ->where('person_id', $this->invite_person_id)
            ->first();

        if ($existingAttendee) {
            session()->flash('error', 'This person has already been invited.');
            return;
        }

        // Create attendee record
        VirtualEventAttendee::create([
            'virtual_event_id' => $this->event->id,
            'person_id' => $this->invite_person_id,
            'rsvp_status' => 'pending',
            'invitation_sent_at' => now(),
        ]);

        $this->invite_person_id = '';
        session()->flash('success', 'Person has been invited successfully.');
        $this->dispatch('attendee-invited');
    }

    public function sendInvitations()
    {
        $this->validate($this->inviteRules);

        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to send invitations.');
            return;
        }

        $sent = 0;
        $errors = [];

        foreach ($this->invite_emails as $email) {
            if (empty($email)) {
                continue;
            }

            // Check if email is already invited
            $existingAttendee = $this->event->attendees()
                ->where('guest_email', $email)
                ->orWhereHas('user', function ($query) use ($email) {
                    $query->where('email', $email);
                })
                ->first();

            if ($existingAttendee) {
                $errors[] = "Email {$email} has already been invited.";
                continue;
            }

            // Create attendee record
            VirtualEventAttendee::create([
                'virtual_event_id' => $this->event->id,
                'guest_email' => $email,
                'guest_name' => explode('@', $email)[0],
                'rsvp_status' => 'pending',
                'invitation_sent_at' => now(),
            ]);

            $sent++;
        }

        $this->showInviteModal = false;
        $this->resetInviteForm();

        if ($sent > 0) {
            session()->flash('success', "Successfully sent {$sent} invitation(s).");
        }

        if (!empty($errors)) {
            session()->flash('warning', implode(' ', $errors));
        }

        $this->dispatch('invitations-sent');
    }

    public function joinEvent()
    {
        if (!$this->event->canJoin()) {
            session()->flash('error', 'The event is not available to join at this time.');
            return;
        }

        if (!$this->attendee || $this->attendee->rsvp_status !== 'accepted') {
            session()->flash('error', 'You must RSVP as accepted to join this event.');
            return;
        }

        // Mark as joined if not already
        if (!$this->attendee->joined_at) {
            $this->attendee->update([
                'joined_at' => now(),
                'attended' => true,
            ]);
        }

        // Redirect to meeting URL
        if ($this->event->join_url) {
            return redirect()->away($this->event->join_url);
        }

        session()->flash('error', 'Meeting link is not available.');
    }

    public function toggleAttendeeList()
    {
        $this->showAttendeeList = !$this->showAttendeeList;
    }

    public function resetRsvpForm()
    {
        $this->rsvp_status = 'accepted';
        $this->rsvp_notes = '';
        $this->guest_name = '';
        $this->guest_email = '';
    }

    public function resetInviteForm()
    {
        $this->invite_emails = [''];
        $this->invite_message = '';
        $this->loadDefaultInviteMessage();
    }

    public function resetFilters()
    {
        $this->statusFilter = 'all';
        $this->search = '';
        $this->resetPage();
    }

    protected function loadDefaultInviteMessage()
    {
        $this->invite_message = "You're invited to join our virtual family reunion: {$this->event->title}. " .
                               "Date: {$this->event->formatted_start_time}. " .
                               "Please RSVP at your earliest convenience.";
    }

    // Computed properties for the view
    public function getRsvpStatusColorProperty()
    {
        if (!$this->attendee) {
            return 'gray';
        }

        return match ($this->attendee->rsvp_status) {
            'accepted' => 'green',
            'declined' => 'red',
            'maybe' => 'yellow',
            default => 'gray',
        };
    }

    public function getRsvpStatusTextProperty()
    {
        if (!$this->attendee) {
            return 'Not Responded';
        }

        return match ($this->attendee->rsvp_status) {
            'accepted' => 'Accepted',
            'declined' => 'Declined',
            'maybe' => 'Maybe',
            default => 'Pending',
        };
    }

    public function getCanInviteProperty()
    {
        return Auth::check() && 
               ($this->event->creator->id === Auth::id() || 
                ($this->attendee && ($this->attendee->is_host || $this->attendee->is_moderator)));
    }
}