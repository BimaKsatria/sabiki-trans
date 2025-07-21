<?php

use App\Models\cars;
use App\Models\User;
use Illuminate\Http\Request;
use Google\Service\Testing\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\UserController;
use Revolution\Google\Sheets\Facades\Sheets;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\Api\BookingsController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\PaymentsController;
use App\Http\Controllers\Api\CustomersController;
use App\Http\Controllers\API\CarCategoryController;
use App\Http\Controllers\Api\PhotoBannerController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Api\DiscountUsageController;
use App\Http\Controllers\Api\RentalDetailsController;
use Google\Client;
use Google\Service\Sheets as GoogleServiceSheets;
use Google\Service\Sheets\Spreadsheet;
use Google\Service\Sheets\ValueRange;




Route::get('/customers/user/{user_id}', [CustomersController::class, 'getByUserId']);
Route::apiResource('rentals', RentalController::class);
Route::apiResource('drivers', DriverController::class);
Route::apiResource('bookings', BookingsController::class);
Route::put('/bookings/{id}', [BookingsController::class, 'update']);
Route::apiResource('discounts', DiscountController::class);
Route::apiResource('customers', CustomersController::class);
Route::get('/ratings', [RatingController::class, 'index']);
Route::post('/ratings', [RatingController::class, 'store']);
Route::get('/ratings/car/{cars_id}', [RatingController::class, 'getByCar']);
Route::put('/ratings/{id}', [RatingController::class, 'update']);
Route::delete('/ratings/{id}', [RatingController::class, 'destroy']);
Route::get('/ratings/car/{cars_id}/average', [RatingController::class, 'averageRating']);

Route::post('/login', function (Request $request) {
    // Handle CORS preflight
    if ($request->isMethod('OPTIONS')) {
        return response()->json()
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type');
    }

    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    // if (!$user || !Hash::check($request->password, $user->password)) {
    //     return response()->json(['message' => 'Login failed'], 401)
    //         ->header('Access-Control-Allow-Origin', '*');
    // }

    if (!$user) {
        return response()->json(['message' => 'Login failed'], 401)
            ->header('Access-Control-Allow-Origin', '*');
    }

    $token = $user->createToken('flutter-token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user])
        ->header('Access-Control-Allow-Origin', '*');
});

// Fixed Register Route with CORS headers
Route::post('/register', function (Request $request) {
    // Handle CORS preflight
    if ($request->isMethod('OPTIONS')) {
        return response()->json()
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type');
    }

    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6'
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password)
    ]);

    $token = $user->createToken('flutter-token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user])
        ->header('Access-Control-Allow-Origin', '*');
});


Route::post('/payments/notification', [PaymentsController::class, 'handleNotification']);

Route::get('/payments/midtrans-status/{order_id}', [PaymentsController::class, 'checkPaymentStatus']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/users', [UserController::class, 'store']);
    Route::get('/me', function (Request $request) {
        return $request->user(); // Check user profile
    });


    Route::post('/payments', [PaymentsController::class, 'store']);
    Route::get('/payments', [PaymentsController::class, 'index']);
    Route::get('/payments/{id}', [PaymentsController::class, 'show']);
    Route::put('/payments/{id}', [PaymentsController::class, 'update']);
    Route::delete('/payments/{id}', [PaymentsController::class, 'destroy']);
    Route::get('/payments-bookinguser/{user_id}', [PaymentsController::class, 'paymentsByUserViaBooking']);


    Route::post('/rentals/{id}/rate', [RentalController::class, 'storeRating']);
});


Route::get('/cars', function (Request $request) {
    $query = $request->query('q');

    return cars::with('category', 'photos')
        ->when($query, function ($q) use ($query) {
            $q->where('brand', 'like', "%{$query}%")
                ->orWhere('model', 'like', "%{$query}%")
                ->orWhere('license_plate', 'like', "%{$query}%");
        })
        ->get()
        ->map(function ($car) {
            return [
                'id' => $car->id ?? 0,
                'brand' => $car->brand,
                'model' => $car->model,
                'price' => $car->price_per_day,
                'rating' => $car->ratings->avg('score') ?? 0,
                'image_url' => $car->thumbnail ?? null,
            ];
        });
});

Route::get('/car-categories', [CarCategoryController::class, 'index']);

Route::get('/cars/{id}', [CarController::class, 'show']);

Route::get('/user-profile/{id}', function ($id) {
    $user = User::with('customer')->findOrFail($id);

    return response()->json([
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->customer->first()->phone ?? '',
        'address' => $user->customer->first()->address ?? '',
    ]);
});

Route::post('/social-login', [SocialLoginController::class, 'handle']);

Route::get('/available-cars', [CarController::class, 'availableCars']);

Route::get('/rentals-with-details', [RentalDetailsController::class, 'index']);

Route::apiResource('discount-usages', DiscountUsageController::class);

Route::post('/discount-usages/record', [DiscountUsageController::class, 'store']);

Route::post('/discounts/check', [DiscountController::class, 'check']);

Route::post('/rentals/check-availability', [RentalController::class, 'checkAvailability']);

Route::get('/get-available-cars', [RentalController::class, 'getAvailableCars']);

Route::get('/rentals', [RentalDetailsController::class, 'update']);

Route::get('/banner', [PhotoBannerController::class, 'index']);

Route::get('/test-sheets', function () {
    try {
        // Initialize the client
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/sabiki-8c7251d530ae.json'));
        $client->addScope(GoogleServiceSheets::SPREADSHEETS);

        // Create the Sheets service
        $service = new GoogleServiceSheets($client);

        // Get values from the sheet
        $response = $service->spreadsheets_values->get(
            env('GOOGLE_SHEET_ID'),
            'Sheet1!A1'
        );

        return response()->json([
            'success' => true,
            'data' => $response->getValues()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

Route::get('/tes-sheets', function () {
    try {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/sabiki-8c7251d530ae.json'));
        $client->addScope(GoogleServiceSheets::SPREADSHEETS);

        $service = new GoogleServiceSheets($client);

        $spreadsheetId = '1_gcgq2aLue_YLWoZ3iPYnwVf1IY7soiHsJ6SeB8Te08';
        $range = 'Sheet1!A1';
        /** @var \Google\Service\Sheets\Resource\SpreadsheetsValues $spreadsheetsValues */
        $spreadsheetsValues = $service->spreadsheets_values;

        $values = $spreadsheetsValues->get($spreadsheetId, $range)->getValues();

        return $values ?: 'Kosong tapi konek';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
