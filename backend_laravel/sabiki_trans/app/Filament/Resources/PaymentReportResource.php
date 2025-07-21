<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\payments;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Log;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Revolution\Google\Sheets\Facades\Sheets;
use App\Filament\Resources\PaymentReportResource\Pages;


class PaymentReportResource extends Resource
{
    protected static ?string $model = payments::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Laporan Keuangan';

    protected static ?string $pluralLabel = 'Laporan Keuangan';

    protected static ?string $modelLabel = 'Laporan Keuangan';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_date')->label('Tanggal')->dateTime(),
                Tables\Columns\TextColumn::make('payment_method')->label('Metode Pembayaran'),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
                Tables\Columns\TextColumn::make('amount')->label('Jumlah')->money('IDR'),
                Tables\Columns\TextColumn::make('order_id')->label('Order ID'),
                Tables\Columns\TextColumn::make('rental.booking.customer.user.name')
                    ->label('Nama Penyewa')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('rental.car.model')
                    ->label('Mobil')->searchable()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->filters([
                // Filter berdasarkan Status pembayaran
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ]),

                // Filter berdasarkan rentang tanggal
                Filter::make('payment_date')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('payment_date', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('payment_date', '<=', $data['until']));
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export-google-sheet')
                    ->label('Export ke Google Sheet')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Export')
                    ->modalDescription('Apakah Anda yakin ingin mengekspor data ke Google Sheet?')
                    ->modalSubmitActionLabel('Ya, Export Sekarang')
                    ->action(function () {
                        try {
                            // Ambil data dan export ke Google Sheet
                            $payments = \App\Models\payments::with(['rental.booking.customer.user', 'rental.car'])->get();

                            $rows = [[
                                'Tanggal',
                                'Metode',
                                'Status',
                                'Jumlah',
                                'Order ID',
                                'Nama Penyewa',
                                'Mobil'
                            ]];

                            foreach ($payments as $p) {
                                $rows[] = [
                                    $p->payment_date ? $p->payment_date->format('Y-m-d H:i:s') : '',
                                    $p->payment_method,
                                    $p->status,
                                    $p->amount,
                                    $p->order_id,
                                    optional($p->rental->booking->customer->user)->name ?? '-',
                                    optional($p->rental->car)->model ?? '-',
                                ];
                            }

                            $client = new \Google\Client();
                            $client->setAuthConfig(storage_path('app/google/sabiki-8c7251d530ae.json'));
                            $client->setScopes([\Google\Service\Sheets::SPREADSHEETS]);
                            $client->setAccessType('offline');

                            $service = new \Google\Service\Sheets($client);
                            $spreadsheetId = env('GOOGLE_SHEET_ID', '1_gcgq2aLue_YLWoZ3iPYnwVf1IY7soiHsJ6SeB8Te08');
                            $range = 'Sheet1!A1';

                            // Kosongkan sheet
                            $service->spreadsheets_values->clear($spreadsheetId, $range, new \Google\Service\Sheets\ClearValuesRequest());

                            // Isi data
                            $body = new \Google\Service\Sheets\ValueRange(['values' => $rows]);
                            $service->spreadsheets_values->update(
                                $spreadsheetId,
                                $range,
                                $body,
                                ['valueInputOption' => 'RAW']
                            );

                            \Filament\Notifications\Notification::make()
                                ->title('Export Berhasil')
                                ->body('Data berhasil diekspor ke Google Sheet.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Export Gagal')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentReports::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'rental.booking.customer.user',
            'rental.car',
        ]);
    }
}
