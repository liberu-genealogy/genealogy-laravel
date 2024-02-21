<?php
namespace App\Filament\Pages\Tenancy;
 
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
 
class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register team';
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
            ]);
    }
    
    protected function handleRegistration(array $data): Team
    {
        $team = Team::create($data);
        
        $team->members()->attach(auth()->user());
        
        return $team;
    }
}
