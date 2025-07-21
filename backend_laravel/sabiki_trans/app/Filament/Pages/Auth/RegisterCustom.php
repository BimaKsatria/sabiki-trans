<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Illuminate\Support\Carbon;
use Filament\Pages\Auth\Register;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\Rules\Password;

class RegisterCustom extends Register
{
    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255),

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required()
                        ->rule(Password::defaults())
                        ->same('password_confirmation'),

                    TextInput::make('password_confirmation')
                        ->label('Konfirmasi Password')
                        ->password()
                        ->required(),
                ]),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        return $data;
    }

    protected function handleRegistration(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make($data['password']),
        ]);

        // Assign role "user" langsung setelah registrasi
        $user->assignRole('user');

        return $user;
    }

}
